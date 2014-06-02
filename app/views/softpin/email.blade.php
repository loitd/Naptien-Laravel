<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
  <table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tr>
      <td height="30px" bgcolor="blue" colspan="2"><h2 color="#FFFFFF"></h2></td>
    </tr>
    <tr><td width="10px"></td></tr>
    <tr>
      <td colspan="2">
        <h3>Thân chào quý khách!</h3>
        <p>{{SITE_NAME}} xin gửi tới quý khách thông tin chi tiết của giao dịch quý khách đã thực hiện tại {{SITE_NAME}}</p>
        <p>Quý khách nên giữ lại email này, trong trường hợp có khiếu nại và thắc mắc của quý khách về giao dịch, chúng tôi
        cần các thông tin để kiểm tra và xác thực</p>
      </td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    <tr>
      <td>Mã giao dịch</td>
      <td><b>{{{$transid}}}</b></td>
    </tr>
    <tr>
      <td>Loại giao dịch</td>
      <td>{{{$transtype}}}</td>
    </tr>
    
    <tr>
      <td>Loại thẻ mua</td>
      <td>{{{$lthe}}}</td>
    </tr>

    <tr>
      <td>Số lượng thẻ mua</td>
      <td>{{{$sluong}}}</td>
    </tr>
    
    <tr>
      <td>Nhà phát hành</td>
      <td>{{{$nph}}}</td>
    </tr>
    <tr>
      <td>Email đăng ký nhận thông tin</td>
      <td>{{{$email}}}</td>
    </tr>
    <tr>
      <td>Ngân hàng đã giao dịch</td>
      <td>{{{$banked}}}</td>
    </tr>
    <tr>
      <td>Kết quả giao dịch ngân hàng</td>
      <td>{{{$bankresult}}}</td>
    </tr>
    <tr>
      <td>Kết quả giao dịch Topup</td>
      <td>{{{$topupresult}}}</td>
    </tr>

    <tr>
      <td>Mã thẻ đã download</td>
      <td>
        <table>
          <tr>
            <th>Mã thẻ</th>
            <th>Thông tin</th>
          </tr>
          @for($i= 0; $i < count($pincodes); $i++)
          <tr>
            <td>{{$pincodes[$i]}}</td>                        
            <td>{{$softpins[$i]}}</td>
          </tr>
          @endfor
        </table>
        
      </td>
    </tr>

    <tr><td colspan="2"><hr></td></tr>
    <tr>
      <td colspan="2">
        <p>Xin cảm ơn quý khách đã sử dụng dịch vụ của chúng tôi</p>
        <h3>{{SITE_NAME}}</h3>
        <p>Mọi thông tin khiếu nại và thắc mắc có thể liên hệ thông qua:</p>
        <p>Hotline: 09001000000</p>
        <p>Skype: abc_hnabc</p>
        <p>Email: admin@abc.com</p>
        <h3>Công ty TNHH và thương mại ABC</h3> 
        D/c: Số 6/9 Ngõ 96 đường Nguyên Phong Sắc, Cầu Giấy, Hà Nội</p>
      </td>
    </tr>
  </table>

</body>
</html>