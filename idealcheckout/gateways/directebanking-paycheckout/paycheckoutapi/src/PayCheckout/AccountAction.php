<?php

namespace PayCheckout;
    
class AccountAction
{
	const NO_ACTION			= 0;
	const CANCEL_ORDER		= 1;
	const CHANGE_ORDER		= 2;
	const CREATE_INVOICE	= 4;
	const ITEM_REFUND		= 8;
	const REFUND			= 16;
}