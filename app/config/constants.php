<?php 
define('NOS', 			'001');
define('AUTHORS', 		'Tran Duc Loi');

define('SITE_URL', 		'http://localhost/naptien/public');
//define('SITE_URL', 		'http://loitd.com/po/naptien/public');
define('SITE_NAME',		'Napthe.info');
define('ADMIN_EMAIL', 	'loitd@vnptepay.com.vn');

/*
	- TOPUP CONSTANTS
*/
define('WS_TOPUP',			'http://itopup-test.megapay.net.vn:8086/ItopupService1.4/services/TopupInterface?wsdl');
define('MERCHANT_USERNAME',	'hieuvdvnn');
define('MERCHANT_PASSWORD',	'30853587656119');
define('MERCHANT_KBT',		'2014/01/20 10:01:03.603'); //Key_birthday_time
define('MERCHANT_DSP',		'ac5747d58aab95abe9e73d35'); //Key_download_softpin

/*
	- TOPUP ERRORS MAPPING
*/
define('ERROR_0',	'Giao dịch thành công.');
define('ERROR_1',	'Lỗi đăng nhập service.');
define('ERROR_2',	'Lỗi khác.');
define('ERROR_3',	'Request ID gửi sang quá ngắn hoặc không có.');
define('ERROR_4',	'Hệ thống bận. Vui lòng thử lại sau.');
define('ERROR_5',	'Không đủ tiền trong tài khoản để thực hiện giao dịch.');
define('ERROR_6',	'Tài khoản nạp tiền không đúng. Vui lòng kiểm tra lại.');
define('ERROR_7',	'Nhà mạng đang bận. Vui lòng thử lại sau.');
define('ERROR_8',	'Sản phẩm yêu cầu không có trong danh mục.');
define('ERROR_9',	'TransID bị trùng lặp.');
define('ERROR_10',	'Không đủ quyền.');
define('ERROR_11',	'Đối tác chưa được đăng ký');
define('ERROR_14',	'Nhà mạng không tồn tại');
define('ERROR_17',	'Giao dịch không tồn tại');
define('ERROR_19',	'Kênh dịch vụ đang tạm khóa.');
define('ERROR_13',	'Giao dịch pending.');

/*
	- SOFTPIN ERRORS MAPPING
*/
define('SOFTPIN_ERROR_3',	'Không đủ softpin.');
define('SOFTPIN_ERROR_4',	'Softpin vượt quá giới hạn.');
define('SOFTPIN_ERROR_5',	'Số tiền vượt quá giới hạn.');
define('SOFTPIN_ERROR_6',	'Tài khoản đích không hợp lệ.');
define('SOFTPIN_ERROR_8',	'Sản phẩm không có trong danh mục.');
define('SOFTPIN_ERROR_9',	'TransID bị trùng lặp.');
define('SOFTPIN_ERROR_10',	'Không đủ quyền.');
define('SOFTPIN_ERROR_11',	'Đối tác không đủ tiền trong tài khoản.');
define('SOFTPIN_ERROR_12',	'Key mã hóa không hợp lệ.');

/*
	- Bank GW

//prod sys
define('MERCHANT_ID', 			'88000019');
define('MERCHANT_SEND_KEY', 	'SQGB4mNzKXUnNJKI5VOStZPY');
define('MERCHANT_RECEIVE_KEY', 	'hMP6JRgPEnuSvRsMY3KFxNRu');
define('WS_BANKING', 			'http://bank.megapay.net.vn:10001/service.asmx?wsdl');
define('URL_RESPONSE', 			SITE_URL . '/banking');
define('URL_RESPONSES', 		SITE_URL . '/bankings');

define('BANKID_OCEANBANK', 		'99020');
define('BANKID_MARITIMEBANK',	'99014');
define('BANKID_BIDV', 			'99015');
define('BANKID_MB', 			'99016');
define('BANKID_VCB', 			'99021');
define('BANKID_VIETINBANK', 	'99008');

*/

//DEV SYS
/**/
define ('MERCHANT_ID', 			'TA123');
define ('MERCHANT_SEND_KEY',	'reesatersuusrtiy12312kty' );
define ('MERCHANT_RECEIVE_KEY',	'k43423553535gsgrthkladgt' );
define ('WS_BANKING', 			'http://14.160.52.14:8015/service.asmx?wsdl' );
define ('URL_RESPONSE', 		SITE_URL . '/banking' );
define ('URL_RESPONSES', 		SITE_URL . '/bankings' );

define('BANKID_OCEANBANK', 		'99020');
define('BANKID_MARITIMEBANK',	'99014');
define('BANKID_BIDV', 			'99015');
define('BANKID_MB', 			'99016');
define('BANKID_VCB', 			'999999');
define('BANKID_VIETINBANK', 	'99008');

/*
CHARGING CONFIGS*/
define('WS_CHARGING', 		'http://charging-test.megapay.net.vn:10001/CardChargingGW_V2.0/services/Services?wsdl');
define('CHG_PARTNERCODE', 	'00426');
define('CHG_PARTNERID', 	'maumuadong');
define('CHG_MPIN', 			'ggdqmfgly');
define('CHG_USER', 			'maumuadong');
define('CHG_PASS', 			'ctxarivaf');
define('CHG_PUBLIC_KEYPATH', base_path() . '/key/Epay_Public_key.pem');
define('CHG_PRIVATE_KEYPATH',base_path() . '/key/kh0016_mykey.pem');





/*
	- CMS CONFIGS CMS
*/
define('GUIDE_CAT_SLUG', 'huong-dan'); 
define('INFO_CAT_SLUG', 'tin-tuc'); 
define('DEFAULT_POST_THUMB', SITE_URL . "/img/viettel2.jpg");

/*
	- ENCRYPTIONS
*/

/*
	- Email config.
*/
define('EMAIL_HOST', 'smtp.gmail.com');
define('EMAIL_PORT', 587);
define('EMAIL_ENCRYPTION', 'tls');
define('EMAIL_USERNAME', 'iolman005@gmail.com');
define('EMAIL_PASSWORD', '19001570');

