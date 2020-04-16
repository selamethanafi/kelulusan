<?php
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}
$server = "localhost"; //ganti sesuai server Anda
$db_user = "root"; //ganti sesuai username Anda
$db_pass = ""; //ganti sesuai password Anda
$database = ""; //ganti sesuatu nama database Anda
$nama_sekolah = 'MA Negeri 2 Semarang';
$tanggal = '2020-04-17 08:00:00';
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
  background: url('https://source.unsplash.com/twukN12EN7c/1920x1080') no-repeat center center fixed;
  -webkit-background-size: cover;
  -moz-background-size: cover;
  background-size: cover;
  -o-background-size: cover;
}
</style>
</head>
<body>
<?php
$nama = '';
$nomor_um ='';
$currentTime = time();//date("Y-m-d H:i:s");
if((isset($_POST['username'])) and (isset($_POST['password'])))
{
	$nomor_um = antiinjeksi($_POST['username']);
	$password = antiinjeksi($_POST['password']);
	$cekuser = mysqli_query($mysqli,"SELECT * FROM `kelulusan` WHERE `nomor_um`='$nomor_um' AND `password`='$password'");
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
			<div class="form-group">
			<div class="alert alert-info">Nomor peserta ujian <strong><?php echo $nomor_um;?></strong></div>
			<div class="alert alert-success">Selamat Ananda <?php echo $nama;?> dinyatakan</div>
			<h1 class="text-success text-center">LULUS</h1>

			<p class="text-center"><a href="kelulusan.php" class="btn btn-primary">Selesai</a></p>
		</div>
		</div>
		<?php
    }
	else
	{
		?>
		<div class="login-form">
		    <form>
        <h4 class="text-center text-danger">Informasi Kelulusan</h4>  
		<h4 class="text-center text-danger"><?php echo $nama_sekolah;?></h4>  
			<div class="form-group">
			<div class="alert alert-warning">Mohon maaf, data kelulusan nomor peserta ujian <strong><?php echo $nomor_um;?></strong> tidak kami temukan</div>
			<div class="alert alert-info">Pastikan nomor peserta ujian dan password sudah dimasukkan dengan benar</div>
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
        <p id="demo"></p>
		<h2 class="text-center">Log in</h2><hr>       
        <h4 class="text-center text-danger">Informasi Kelulusan</h4>  
		<h4 class="text-center text-danger"><?php echo $nama_sekolah;?></h4>  
		<?php
		 //echo $currentTime.'<br>'.strtotime($tanggal).' '.$tanggal;
		if ($currentTime > strtotime($tanggal)) 
		{?>
        <div class="form-group">
            <input type="text" name="username" class="form-control" placeholder="Nomor Peserta Ujian Madrasah" required="required">
        </div>
        <div class="form-group">
            <input type="password" name="password" class="form-control" placeholder="Password" required="required">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Log in</button>
        </div>
        <?php
		}
		else
		{
    		  echo '<hr><h4 class="text-info text-center">Sekarang belum waktunya kelulusan</h4>
    		  			<p class="text-center"><a href="kelulusan.php" class="btn btn-primary">Muat Ulang</a></p>';
		}?>
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
  document.getElementById("demo").innerHTML = "<p class=\"text-center\">Kelulusan akan diumumkan <?php echo $tanggal;?> ("+ days + " hari " + hours + " jam "
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
</html>                                		                            
