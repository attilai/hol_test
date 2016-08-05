<?php
	class Hellodialog_Tracker_Model_Cron {

		 // 2.400 per day (job every 15 minutes), this ensures we stay within Google's gecode limits (2500/24hrs)
		private static $orders_per_job = 25;

		public static function orders_per_job() {
			return self::$orders_per_job;
		}

		public static function current_page() {
			return (int)Mage::getStoreConfig('hellodialog/synchronize_history/current_page');
		}

		public static function sync() {

			if (!class_exists("HelloDialog_Tracker_Model_Observer", false)) {
				require_once "Observer.php";
			}

			$configurator = new Mage_Core_Model_Config();
			$configurator->saveConfig('hellodialog/synchronize_history/cron_lastrun', time(), 'default', 0);

			// check if this job is enabled in the first place
			if (!Mage::getStoreConfig('hellodialog/synchronize_history/enabled')) {
				return;
			}

			// configuration
			$orders_per_job = self::orders_per_job();
			$current_page   = self::current_page();
			$hd_observer    = new HelloDialog_Tracker_Model_Observer();
			$total          = $orders = Mage::getModel('sales/order')->getCollection()
										->addFieldToFilter('status', 'complete')
										->getSize();

			// mark start
			if ($current_page == 0) {
				$configurator->saveConfig('hellodialog/synchronize_history/cron_started', time(), 'default', 0);
			}

			// check if done
			if (self::check_if_done($configurator, $total, $current_page)) {
				return;
			}

			// process orders
			self::log('==============================================');
			self::log('Hellodialog Order Sync '.date('d-m-Y @ H:i:s'));
			self::log('Processing orders (LIMIT '.($current_page * self::orders_per_job()).', '.self::orders_per_job().')');

			$orders = Mage::getModel('sales/order')->getCollection()
				->addFieldToFilter('status', 'complete')     // only import completed orders
				->addAttributeToSort('increment_id', 'DESC') // import most recent orders first
				->setCurPage($current_page + 1)              // magento starts numbering pages at 1 (instead of 0)
				->setPageSize(self::orders_per_job());

			$current_page++;

			foreach ($orders as $order) {
				self::log("- processing order ".$order->getIncrementId()." (".$order->getCustomerName().")");
				$hd_observer->sync_order($order);
			}

			// update current page in config
			if (self::check_if_done($configurator, $total, $current_page)) {
				return;
			}
			$configurator->saveConfig('hellodialog/synchronize_history/current_page', $current_page, 'default', 0);
		}

		private static function log($l) {
			Mage::log($l, null, 'hellodialog.log');
		}

		private static function check_if_done($configurator, $total, $page) {
			if ($total <= $page * self::orders_per_job()) {
				self::log("Hellodialog Order Sync >> DONE <<");
				$configurator->saveConfig('hellodialog/synchronize_history/enabled', 0, 'default', 0);
				return true;
			}
			return false;
		}
	}