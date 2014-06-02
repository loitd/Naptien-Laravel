<?php 
/*
	Transaction types array
*/

$transType 	= array(
	'MTOPUP' 	=> 'Nạp tiền cho di động',
	'GTOPUP'	=> 'Nạp tiền cho game',
	'SOFTPIN'	=> 'Mua mã thẻ điện thoại/game',
);

/*
	Banks array
*/
$bankCode	= array(
	'99020'		=> 'Ocean Bank',
	'99014'		=> 'Maritime Bank',
	'99015'		=> 'BIDV',
	'99016'		=> 'MB',
	'99021'		=> 'Vietcombank',
	'99008'		=> 'Vietinbank',
	'999999'	=> 'Vietinbank' //for test gw

); 

/*
	Transaction status array
*/
$transStatus = array(
	'S99'		=> 'Giao dịch được khởi tạo bởi người dùng. Chưa gọi được Bank ws',
	'S98'		=> 'Giao dịch đã gửi sang bank ws thành công. Chưa có kết quả trả về từ Bank WS hoặc kết quả trả về thất bại', //xem them bank_response de biet chi tiet loi
	'S97'		=> 'Giao dịch ngân hàng thành công. Đang đợi người dùng confirm', //Giao dịch đã gửi sang bank ws thành công. Đang đợi kết quả từ bank gw
	'S96'		=> 'Giao dịch ngân hàng thành công. Đang đợi người dùng confirm', //for re-check
	'S95'		=> 'Giao dịch đã được confirm bởi người dùng. Đang tiến hành nạp tiền/mua mã thẻ',
	'S94'		=> 'Giao dịch đã được thực hiện topup và có kết quả thành công',
	'S93'		=> 'Giao dịch đã được thực hiện topup và thất bại',

);
/*
	Topup result to cus array
*/
$topupResult = array(
	'0'			=> 'Thành công',
	'-1'		=> 'Thất bại',
	'-2'		=> 'Thất bại',
	'-3'		=> 'Thất bại',
	'-4'		=> 'Thất bại',
	'-5'		=> 'Thất bại',
	'-6'		=> 'Thất bại',
	'-7'		=> 'Thất bại',
	'-8'		=> 'Thất bại',
	'-9'		=> 'Thất bại',
	'-10'		=> 'Thất bại',
	'-11'		=> 'Thất bại',
	'-13'		=> 'Giao dịch đang chờ xử lý',
	'-14'		=> 'Thất bại',
	'-17'		=> 'Thất bại',
	'-19'		=> 'Thất bại'
);

$softpinProductID = array(
	'VTT10'		=> '1',
	'VTT20'		=> '2',
	'VTT30'		=> '3',
	'VTT50'		=> '4',
	'VTT100'	=> '5',
	'VTT200'	=> '6',
	'VTT300'	=> '7',
	'VTT500'	=> '8',

	'SFN10'		=> '9',
	'SFN20'		=> '10',
	'SFN30'		=> '11',
	'SFN50'		=> '12',
	'SFN100'	=> '13',
	'SFN200'	=> '14',
	'SFN300'	=> '15',
	'SFN500'	=> '16',

	'VNP10'		=> '17',
	'VNP20'		=> '18',
	'VNP30'		=> '19',
	'VNP50'		=> '20',
	'VNP100'	=> '21',
	'VNP200'	=> '22',
	'VNP300'	=> '23',
	'VNP500'	=> '24',

	'VMS10'		=> '25',
	'VMS20'		=> '26',
	'VMS30'		=> '27',
	'VMS50'		=> '28',
	'VMS100'	=> '29',
	'VMS200'	=> '30',
	'VMS300'	=> '31',
	'VMS500'	=> '32',

	'BEE10'		=> '74',
	'BEE20'		=> '75',
	'BEE30'		=> '76',
	'BEE50'		=> '77',
	'BEE100'	=> '78',
	'BEE200'	=> '79',
	'BEE300'	=> '80',
	'BEE500'	=> '81',

	'VNM10'		=> '82',
	'VNM20'		=> '83',
	'VNM30'		=> '84',
	'VNM50'		=> '85',
	'VNM100'	=> '86',
	'VNM200'	=> '87',
	'VNM300'	=> '88',
	'VNM500'	=> '89',
	
	'VCOIN20'	=> '41',
	'VCOIN50'	=> '42',
	'VCOIN100'	=> '43',
	'VCOIN200'	=> '44',
	'VCOIN300'	=> '45',
	'VCOIN500'	=> '46',

	'GATE10'	=> '100',
	'GATE20'	=> '57',
	'GATE30'	=> '58',
	'GATE50'	=> '59',
	'GATE100'	=> '101',
	'GATE200'	=> '102',
	'GATE500'	=> '103',
	
	'ZING20'	=> '69',
	'ZING60'	=> '70',
	'ZING120'	=> '73',

	'ONCASH20'	=> '90',
	'ONCASH60'	=> '91',
	'ONCASH100'	=> '92',
	'ONCASH200'	=> '93',
	'ONCASH500'	=> '106',
	

	
);

return array(
	'transType' 		=> $transType,
	'bankCode'			=> $bankCode,
	'transStatus'		=> $transStatus,
	'topupResult'		=> $topupResult,
	'softpinProductID'	=> $softpinProductID
);