<?php
/*
* 2007-2012 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/osl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2012 PrestaShop SA
*  @version  Release: $Revision: 6844 $
*  @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class OrderPaymentCore extends ObjectModel
{
	public $id_order;
	public $id_currency;
	public $id_order_invoice;
	public $amount;
	public $payment_method;
	public $conversion_rate;
	public $transaction_id;
	public $card_number;
	public $card_brand;
	public $card_expiration;
	public $card_holder;
	public $date_add;

	/**
	 * @see ObjectModel::$definition
	 */
	public static $definition = array(
		'table' => 'order_payment',
		'primary' => 'id_order_payment',
		'fields' => array(
			'id_order' => 			array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_currency' => 		array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
			'id_order_invoice' => 	array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
			'amount' => 			array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice', 'required' => true),
			'payment_method' => 	array('type' => self::TYPE_STRING, 'validate' => 'isGenericName'),
			'conversion_rate' => 	array('type' => self::TYPE_INT, 'validate' => 'isFloat'),
			'transaction_id' => 	array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 254),
			'card_number' => 		array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 254),
			'card_brand' => 		array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 254),
			'card_expiration' => 	array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 254),
			'card_holder' => 		array('type' => self::TYPE_STRING, 'validate' => 'isAnything', 'size' => 254),
			'date_add' => 			array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		),
	);

	public function add($autodate = true, $nullValues = false)
	{
		if (parent::add($autodate, $nullValues))
		{
			Hook::exec('actionPaymentCCAdd', array('paymentCC' => $this));
			return true;
		}
		return false;
	}

	/**
	* Get the detailed payment of an order
	* @param int $id_order
	* @return array
	*/
	public static function getByOrderId($id_order)
	{
		return Db::getInstance()->executeS('
			SELECT *
			FROM `'._DB_PREFIX_.'order_payment`
			WHERE `id_order` = '.(int)$id_order);
	}

	/**
	 * Get Order Payments By Invoice ID
	 * @static
	 * @param $id_invoice Invoice ID
	 * @return Collection Collection
	 */
	public static function getByInvoiceId($id_invoice)
	{
		$payments = new Collection('OrderPayment');
		$payments->where('id_order_invoice', '=', $id_invoice);
		return $payments;
	}
}

