<div class="container-fluid">
    <div class="row header-section">
        <div class="col-md-6 header-content">
            <h1><?php echo $header_name ?></h1>
            <p><?php echo $header_description ?></p>
            <p><strong>Dates:</strong> <?php echo $header_dates ?></p>
        </div>
        <div class="col-md-6 header-image"></div>
    </div>
</div>

<style>
    .header-section {
        display: flex;
        align-items: center;
        background-color: #3D6F4D;
        color: white;
        padding: 50px;
    }
    .header-content {
        flex: 1;
        padding-right: 30px;
    }
    .header-image {
        flex: 1;
        background: url('<?php echo $header_image ?>') no-repeat center center;
        background-size: cover;
        min-height: 400px;
    }
    .header-content h1 {
        font-weight: bold;
    }
    .header-content p {
        margin-bottom: 20px;
    }
    .header-content strong {
        font-weight: bold;
    }
</style>