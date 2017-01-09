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
                    <td align="center" style="font-size: 32px; font-weight: 300; line-height: 2.5em; color: #1e6ea2; font-family: sans-serif;">重新設定密碼</td>
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
                      TechComm 已收到您申請重新設定密碼的要求，若您無提出申請請忽略此封信件，若要重新設定密碼請於收到信件10分鐘內點選以下連結來重設:</p>
                      <p><a href="{{ $dataResult['url'] }}">{{ $dataResult['url'] }}</a> </p>
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




