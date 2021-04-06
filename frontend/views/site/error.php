<style>
  .error {
    background: none repeat scroll 0 0 #DFDFDF;
    color: #000000;
    font-family: Helvetica;
    font-size: 150px;
    line-height: 2;
    padding: 50px 0;
    text-align: center;
  }

  .error p {
    font-size: 54px;
  }


  .error.five-zero-three {
    background: transparent none repeat scroll 0 0;
    font-size: 50px;
    line-height: 76px;
    margin: 0 auto;
    width: 50%;
  }

  .error.five-zero-three p::after {
    background: red none repeat scroll 0 0;
    content: "";
    display: block;
    height: 3px;
    margin: 0 auto;
    width: 100px;
  }

  .error.five-zero-three p {
    color: red;
    display: inline-block;
    font-size: 75px;
    font-weight: bold;
  }

  .error.five-zero-three span {
    color: #404040;
    display: block;
  }

  @media (max-width: 1024px) {
    .error.five-zero-three {
      width: 650px;
    }
  }

  @media (max-width: 700px) {
    .error.five-zero-three {
      padding-left: 15px;
      padding-right: 15px;
      width: 100%;
    }
  }

  @media (max-width: 450px) {
    .error.five-zero-three {
      font-size: 35px;
      line-height: 55px;
    }
  }
</style>



<div class="error">
  <?php if (is_array($exception)) { ?>
    <div class="error five-zero-three">
      <p class="lead"><?php echo $exception['statusCode']; ?></p>
      <span><?php echo $error; ?>
    </div>


  <?php } else { ?>
    <p class="lead"><?php echo $exception->statusCode; ?></p>
    Not Found !!
  <?php } ?>
</div>