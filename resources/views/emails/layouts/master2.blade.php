<!doctype html>
<!--Quite a few clients strip your Doctype out, and some even apply their own. Many clients do honor your doctype and it can make things much easier if you can validate constantly against a Doctype.-->
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Email template By Adobe Dreamweaver CC</title>

<!-- Please use an inliner tool to convert all CSS to inline as inpage or external CSS is removed by email clients -->
<!-- important in CSS is used to prevent the styles of currently inline CSS from overriding the ones mentioned in media queries when corresponding screen sizes are encountered -->

<style type="text/css">
body {
	margin: 0;
}
body, table, td, p, a, li, blockquote {
	-webkit-text-size-adjust: none!important;
	font-family: sans-serif;
	font-style: normal;
	font-weight: 400;
}

	a{ text-decoration:none; color:#ad0020;}
	a:hover{ color:#830002}
	
	p{ color:#666}

button {
	width: 90%;
}

@media screen and (max-width:600px) {
/*styling for objects with screen size less than 600px; */
body, table, td, p, a, li, blockquote {
	-webkit-text-size-adjust: none!important;
	font-family: sans-serif;
}
table {
	/* All tables are 100% width */
	width: 100%;
}
.footer {
	/* Footer has 2 columns each of 48% width */
	height: auto !important;
	max-width: 48% !important;
	width: 48% !important;
}
table.responsiveImage {
	/* Container for images in catalog */
	height: auto !important;
	max-width: 30% !important;
	width: 30% !important;
}
table.responsiveContent {
	/* Content that accompanies the content in the catalog */
	height: auto !important;
	max-width: 66% !important;
	width: 66% !important;
}
.top {
	/* Each Columnar table in the header */
	height: auto !important;
	max-width: 48% !important;
	width: 48% !important;
}
.catalog {
	margin-left: 0%!important;
}
}

@media screen and (max-width:480px) {
/*styling for objects with screen size less than 480px; */
body, table, td, p, a, li, blockquote {
	-webkit-text-size-adjust: none!important;
	font-family: sans-serif;
}
table {
	/* All tables are 100% width */
	width: 100% !important;
	border-style: none !important;
}
.footer {
	/* Each footer column in this case should occupy 96% width  and 4% is allowed for email client padding*/
	height: auto !important;
	max-width: 96% !important;
	width: 96% !important;
}
.table.responsiveImage {
	/* Container for each image now specifying full width */
	height: auto !important;
	max-width: 96% !important;
	width: 96% !important;
}
.table.responsiveContent {
	/* Content in catalog  occupying full width of cell */
	height: auto !important;
	max-width: 96% !important;
	width: 96% !important;
}
.top {
	/* Header columns occupying full width */
	height: auto !important;
	max-width: 100% !important;
	width: 100% !important;
}
.catalog {
	margin-left: 0%!important;
}
button {
	width: 90%!important;
}
}
</style>
</head>
<body yahoo="yahoo">
<table width="100%"  cellspacing="0" cellpadding="0">
  <tbody>
    <tr>
      <td><table width="600"  align="center" cellpadding="0" cellspacing="0" style="
    border: 1px solid #1e6ea2;
">
          <!-- Main Wrapper Table with initial width set to 60opx -->
          <tbody>
            <tr>
              <td>
                 	<table style="
    border-bottom: 1px solid #1e6ea2" class="top" width="100%"  align="left" cellpadding="0" cellspacing="0" >
                  <!-- First header column with Logo -->
                  	<tbody>
                    <tr>
                      
                    </tr>
                  	</tbody>
              	 	</table>
              </td>
            </tr>
            @yield('content')
         
            <tr bgcolor="#1e6ea2">
              <td><table class="footer" width="48%"  align="left" cellpadding="0" cellspacing="0">
                  <!-- First column of footer content -->
                  <tr>
                    <td><p align="center"  style="font-size: 18px; font-weight:300; line-height: 2.5em; color: #fff; font-family: sans-serif;">國立台灣大學 生命科學院<br>
                    </p>
					</td>
                  </tr>
                </table>
                <table class="footer" width="48%"  align="left" cellpadding="0" cellspacing="0">
                  <!-- Second column of footer content -->
                  <tr>
                    <td>
                      <p align="right" style="font-family: sans-serif;"> <a style="color:#fff; text-decoration:none; padding-left:20px; font-size:14px;" href="{{ asset('/') }}">首頁</a> <a style="color:#fff; text-decoration:none; padding-left:20px;  font-size:14px;" href="{{ asset('contact_us') }}">聯絡我們</a> <a style="color:#fff; text-decoration:none; font-size:14px; padding-left:20px; padding-right:20px; " href="{{ asset('member') }}">會員專區</a></p></td>
                  </tr>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>
