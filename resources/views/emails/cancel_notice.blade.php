@extends('emails.layouts.master')


@section('content')
<tr> 
              <!-- HTML Spacer row -->
              <td style="font-size: 0; line-height: 0;" height="20"><table width="96%" align="left"  cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
                  </tr>
                </table></td>
            </tr>
            <tr> 
              <!-- HTML Spacer row -->
              <td style="font-size: 0; line-height: 0;" height="20"><table width="96%" align="left"  cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
                  </tr>
                </table></td>
            </tr>
            <tr> 
              <!-- Introduction area -->
              <td><table width="96%"  align="left" cellpadding="0" cellspacing="0">
                  <tr> 
                    <!-- row container for TITLE/EMAIL THEME -->
                    <td align="center" style="font-size: 32px; font-weight: 300; line-height: 2.5em; color: #1e6ea2; font-family: sans-serif;">系統訊息通知</td>
                  </tr>
                  
                  <tr>
                    <td style="font-size: 0; line-height: 0;" height="20"><table width="96%" align="left"  cellpadding="0" cellspacing="0">
                        <tr> 
                          <!-- HTML Spacer row -->
                          <td style="font-size: 0; line-height: 0;" height="20">&nbsp;</td>
                        </tr>
                      </table></td>
                  </tr>
                  <tr> 
                    <!-- Row container for Intro/ Description -->
                    <td align="left" style="font-size: 16px; font-style: normal; font-weight: 100; color: #666; line-height: 1.8; text-align:justify; padding:10px 20px 0px 20px; font-family: sans-serif;"><p>親愛的 {{ $dataResult['user'] }}先生/ 小姐，您好：<br>
                      您所管理已的儀器{{ $dataResult['instrument'] }}已有使用者取消預約時段，詳細資料如下:</p>
                      <p>使用者:{{ $dataResult['cancel_member']['name'] }}</p>
                      <p>email:{{ $dataResult['cancel_member']['email'] }}</p>
                      <p>指導教授:{{ $dataResult['cancel_member']['pi_name'] }}</p>
                      <p>取消日期:{{ $dataResult['cancel_date'] }}</p>
                      <p>取消時段:{{ $dataResult['cancel_section']['start_time'] }} ~ {{ $dataResult['cancel_section']['end_time'] }}</p>
                      <p></p>
                      <p>科技共同空間 敬啟<br>
                      </p></td>
                  </tr>
                </table></td>
            </tr>
            <tr> 
              <!-- HTML Spacer row -->
            
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
@endsection




