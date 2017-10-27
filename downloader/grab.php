<br>
<?php
error_reporting(0);
if(isset($_POST['url'])) {
$urlmedia = $_POST['url'];
$get = file_get_contents('http://api.instagram.com/oembed/?url='.$urlmedia);
if(preg_match('#provider_url#',$get)) {
$obj = json_decode($get,true);
$media = $obj['media_id'];
$author = $obj['author_name'];
$gambar = $obj['thumbnail_url'];
$embed = $obj['html'];
$authid = $obj['author_id'];
$authlink = $obj['author_url'];

$res1 = '
<div class="col-lg-5">
	<div class="panel panel-warning">
                <div class="panel-heading">
                <h3 class="panel-title">
               Preview
                </h3>
                </div>
                <div class="panel-body">
				'.$embed.'
				</div>
	</div>
	</div>
	';
	
$res2 = '
<div class="col-lg-7">
        <div class="panel panel-success">
                <div class="panel-heading">
                <h3 class="panel-title">
               Photo Information
                </h3>
                </div>
                <div class="panel-body">
<table class="table table-bordered table-hover">
	<tbody>
		<tr>
<td>MEDIA ID</td>
<td><font color=green>'.$media.'</font></td>
		</tr>
		
<tr>
<td>USERNAME</td>
<td><font color=green>@'.$author.'</font></td>
		</tr>
<tr>
<td>USER ID</td>
<td><font color=green>'.$authid.'</font></td>

</tr>
<tr>
<td>USER URL</td>
<td><font color=green>'.$authlink.'</font></td>
		</tr>
		<tr>
<td style="margin-top:10px;">DOWNLOAD</td>
<td>
<form target="_blank" action="get.php?url='.$gambar.'" method="post">
<input style="padding:5px;width:400px;text-align:center" class="btn btn-danger btn-block" type="submit" name="ambil" value="DOWNLOAD INSTAGRAM PHOTO">
</form>
</td>
		</tr>
	</tbody>
</table>

</div>  </div>   </div> 
';
}else {
	$res1 = '
<div style="border-radius:0px;padding:4px" class="alert alert-danger">
  <center><strong>FAILED !</strong> Tidak ada MEDIA pada link yang dimasuk kan, cek kembali URL MEDIA. Ini terjadi mungkin karena URL MEDIA Tidak Valid. 
';
$res2 = '
Atau akun yang ingin anda ambil gambarnya dalam mode Private, pastikan akun Pemilik URL Media tersebut dalam mode public.
</center>
</div>
';	

}
}
?>
<div class="container">
<?php 

echo  $res1;   
echo  $res2; ?>
	
	 				
</div>             
</body>
</html>
