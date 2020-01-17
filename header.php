<?php session_start(); ?>
<div class="container cover">
  <header class="masthead mb-auto clearfix">
    <div class="inner">
      <h3 class="masthead-brand float-left">Soleil Stocks <?php echo " - " .$_SESSION['location_name']; ?></h3>
      <nav class="nav nav-masthead float-right justify-content-center">
        <a class="nav-link active" href="./">Stocks</a>
        <!-- <a class="nav-link active" href="./stocks.php">Stocks</a> -->
        <!-- <a class="nav-link active" href="./transfers.php">Stocks</a> -->
        <a class="nav-link active" href="./po.php">Purchase Order</a>
        <a class="nav-link active" href="./logout.php">Logout</a>
      </nav>
    </div>
  </header>
</div>
