<?php
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}
function tanggal_panjang($tanggale)
{
	$str= $tanggale;
	$postedyear=substr($str,0,4);
	$postedmonth=substr($str,5,2);
	$postedday=substr($str,8,2);
	if($postedday<10)
	{
		$postedday = substr($postedday,-1);
	}
	$bulan='';
	if ($postedmonth=="01")
	{
    	$bulan = "Januari";
	}
	if ($postedmonth=="02")
	{
		$bulan = "Februari";
	}
	if ($postedmonth=="03")
	{
		$bulan = "Maret";
	}
	if ($postedmonth=="04")
	{
		$bulan = "April";
	}
	if ($postedmonth=="05")
	{
		$bulan = "Mei";
	}
	if ($postedmonth=="06")
	{
		$bulan = "Juni";
	}
	if ($postedmonth=="07")
	{
		$bulan = "Juli";
	}
	if ($postedmonth=="08")
	{
		$bulan = "Agustus";
	}
	if ($postedmonth=="09")
	{
		$bulan = "September";
	}
	if ($postedmonth=="10")
	{
		$bulan = "Oktober";
	}
	if ($postedmonth=="11")
	{
		$bulan = "November";
	}
	if ($postedmonth=="12")
	{
		$bulan = "Desember";
	}
	$tanggalpanjang = "$postedday $bulan $postedyear";	
	return $tanggalpanjang;	
}
$server = "localhost"; //ganti sesuai server Anda
$db_user = "root"; //ganti sesuai username Anda
$db_pass = ""; //ganti sesuai password Anda
$database = ""; //ganti sesuatu nama database Anda
$nama_sekolah = 'MA Negeri 2 Semarang';
$tanggal = '2020-05-01 15:51:00';
$pukul = substr($tanggal,-8);
$tanggal_panjang = tanggal_panjang($tanggal);
// Koneksi dan memilih database di server
$mysqli = new mysqli($server,$db_user,$db_pass,$database) or die("Koneksi gagal");

function antiinjeksi($text){
        global $mysqli;
        $safetext = mysqli_real_escape_string($mysqli,stripslashes(strip_tags(htmlspecialchars($text,ENT_QUOTES))));
        return $safetext;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Sistem Informasi Kelulusan <?php echo $nama_sekolah;?></title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
<style type="text/css">
	.login-form {
		width: 340px;
    	margin: 50px auto;
	}
    .login-form form {
    	margin-bottom: 15px;
        background: #f7f7f7;
        box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        padding: 30px;
    }
    .login-form h2 {
        margin: 0 0 15px;
    }
    .form-control, .btn {
        min-height: 38px;
        border-radius: 2px;
    }
    .btn {        
        font-size: 15px;
        font-weight: bold;
    }
	/* Set a background image by replacing the URL below */
body {
  background: url('https://t.man2semarang.sch.id/latar.jpg') no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  background-size: cover;
  -o-background-size: cover;
}
</style>
</head>
<body>
<?php
//https://source.unsplash.com/twukN12EN7c/1920x1080
$nama = '';
$nomor_um ='';
$currentTime = time();//date("Y-m-d H:i:s");
if((isset($_POST['username'])) and (isset($_POST['password']))  and (isset($_POST['tgllhr'])))
{
    $nomor_um = antiinjeksi($_POST['username']);
    $password = antiinjeksi($_POST['password']);
	$tanggallahir =   antiinjeksi($_POST['tgllhr']);
	$dd = substr($tanggallahir,0,2);
	$mm = substr($tanggallahir,3,2);
	$yy = substr($tanggallahir,6,4);
	$tgllhr = $yy.'-'.$mm.'-'.$dd;//.'**'.$tanggal;
//	die($tgllhr);
    $cekuser = mysqli_query($mysqli,"SELECT * FROM `kelulusan` WHERE `nomor_um`='$nomor_um' AND `password`='$password' and `tgllhr`='$tgllhr'");
    $jmluser = mysqli_num_rows($cekuser);
    $data = mysqli_fetch_array($cekuser);
    if($jmluser > 0)
    {
        $nama  = $data['nama'];
		?>
		<div class="login-form">
			<form>
			<h4 class="text-center text-danger">Informasi Kelulusan</h4>  
			<h4 class="text-center text-danger"><?php echo $nama_sekolah;?></h4>  
			<?php
		    if ($currentTime > strtotime($tanggal)) 
			{?>
				<div class="form-group">
				<div class="alert alert-info">Nomor peserta ujian <strong><?php echo $nomor_um;?></strong> <strong></div>
				<div class="alert alert-success text-center">Selamat Ananda <h3><?php echo $nama;?></h3> dinyatakan</div>
				<h1 class="text-success text-center">LULUS</h1>
				<p class="text-center"><a href="kelulusan.php" class="btn btn-primary">Selesai</a></p>
				</div>
				<?php
			mysqli_query($mysqli,"UPDATE `kelulusan` SET `dilihat` = '1' WHERE `nomor_um`='$nomor_um'");
			}
			else
			{?>
		<p id="demo"></p>
				<div class="form-group">
				<div class="alert alert-info">Nomor peserta ujian <strong><?php echo $nomor_um;?></strong> <strong></div>
				<div class="alert alert-success text-center"><h3><?php echo $nama;?></h3>
				</div>
				<hr><h4 class="text-info text-center">Sekarang belum waktunya kelulusan</h4>
    		  			<p class="text-center"><a href="kelulusan.php" class="btn btn-primary">Muat Ulang</a></p>
						<?php
			}
			echo '</form>';
	}
	else
	{
	    ?>
   		<div class="login-form">
	    <form>
        <h4 class="text-center text-danger">Informasi Kelulusan</h4>  
		<h4 class="text-center text-danger"><?php echo $nama_sekolah;?></h4>  
			<div class="form-group">
			<div class="alert alert-warning">Mohon maaf, data kelulusan nomor peserta ujian <strong><?php echo $nomor_um;?></strong> <?php echo 'Lahir '.tanggal_panjang($tgllhr);?></strong> tidak kami temukan</div>
			<div class="alert alert-info">Pastikan nomor peserta ujian, password, dan tanggal lahir sudah dimasukkan dengan benar</div>
			</div>
			<p class="text-center"><a href="kelulusan.php" class="btn btn-primary">Coba lagi</a></p>
		</div>
		</div>
	    <?php
	}
}
else
{
?>
<div class="login-form">
        <form action="kelulusan.php" method="post">
 		<h2 class="text-center">Masuk</h2><hr>       
        <h4 class="text-center text-danger">Informasi Kelulusan</h4>  
		<h4 class="text-center text-danger"><?php echo $nama_sekolah;?></h4> <hr>        
		    <div class="form-group">
		            <label>Nomor Peserta Ujian Madrasah</label>
            <input type="text" name="username" class="form-control" placeholder="Nomor Peserta Ujian Madrasah" id="nomor" required="required" autofocus>
        </div>
        <div class="form-group">
		            <label>Password Ujian Madrasah</label>
            <input type="password" name="password" class="form-control" placeholder="Password" required="required">
        </div>
		        <div class="form-group">
		            <label>Tanggal Lahir</label>
            <input type="text" name="tgllhr" class="form-control" placeholder="Tanggal lahir" required="required" id="tgllhr">
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block">Masuk</button>
        </div>
			<p id="demo"></p>
    </form>

</div>
<?php
}
?>
</body>
<?php
if ($currentTime < strtotime($tanggal)) 
{?>
<!-- Display the countdown timer in an element -->

<script>
// Set the date we're counting down to
var countDownDate = new Date("<?php echo $tanggal;?>").getTime();

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Display the result in the element with id="demo"
  document.getElementById("demo").innerHTML = "<p class=\"text-center\">Kelulusan akan diumumkan</p><p class=\"text-center\"><?php echo $tanggal_panjang;?></p><p class=\"text-center\"><?php echo $pukul;?></p><p class=\"text-center\">("+ days + " hari " + hours + " jam "
  + minutes + " menit " + seconds + " detik)</p>";

  // If the count down is finished, write some text
  if (distance < 0) {
    clearInterval(x);
    location.reload();
  }
}, 1000);
</script>

<?php
}?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
        <script src="jquery.maskedinput.js"></script>
        <script>
        jQuery(function($){
			$("#tgllhr").mask("99-99-9999")
            $("#nomor").mask("999.99.99.999.999");
        });
        </script>
</html>                                		                            