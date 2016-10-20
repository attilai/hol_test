<?php
	class Hellodialog_Tracker_Model_Cron {

		 // 2.400 per day (job every 15 minutes), this ensures we stay within Google's gecode limits (2500/24hrs)
		private static $orders_per_job = 25;

		// 9600 per day (job every 15 minutes) if we're not using Google's geocode
		private static $orders_per_job_sans_geocoding = 100;

		public static function orders_per_job() {
			return Mage::getStoreConfig('hellodialog/general/geocode') ? self::$orders_per_job : self::$orders_per_job_sans_geocoding;
		}

		public static function current_page() {
			return (int)Mage::getStoreConfig('hellodialog/synchronize_history/current_page');
		}

		public static function sync() {

			if (!class_exists("HelloDialog_Tracker_Model_Observer", false)) {
				require_once "Observer.php";
			}

			$configurator = Mage::getConfig();
			$configurator->saveConfig('hellodialog/synchronize_history/cron_lastrun', time(), 'default', 0);

			// check if this job is enabled in the first place
			if (!Mage::getStoreConfig('hellodialog/synchronize_history/enabled')) {
				return;
			}

			// configuration
			$current_page   = self::current_page();
			$hd_observer    = new HelloDialog_Tracker_Model_Observer();
			$total          = $orders = Mage::getModel('sales/order')->getCollection()
										->addFieldToFilter('status', 'complete')
										->getSize();

			// mark start
			if ($current_page == 0) {
				$configurator->saveConfig('hellodialog/synchronize_history/cron_started', time(), 'default', 0);
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
				$start = microtime(true);
				$hd_observer->sync_order($order);
				self::log("- order #{$order->getIncrementId()} processed in " . round(((microtime(true) - $start) * 1000), 2).'ms');
			}

			// update current page in config
			self::check_if_done($configurator, $total, $current_page);
			$configurator->saveConfig('hellodialog/synchronize_history/current_page', $current_page, 'default', 0);
			Mage::app()->getConfig()->reinit();
		}

		private static function log($l) {
			Mage::log($l, null, 'hellodialog.log');
		}

		private static function check_if_done($configurator, $total, $page) {
			if ($total <= $page * self::orders_per_job()) {
				self::log("Hellodialog Order Sync >> DONE <<");
				$configurator->saveConfig('hellodialog/synchronize_history/enabled', 0, 'default', 0);
			}
		}
	}
