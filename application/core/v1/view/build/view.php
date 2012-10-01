<div class="span3">
    <div class="row-fluid">
        <div class="span12">
            <div class="well" >
                <p>Use the controls below to toggle different Build modes. You can select a picture below to add it to your gallery.</p>
                <div class="btn-group" data-toggle="buttons-radio">
                    <button type="button" class="btn" id="btn-build">Build Walls</button>
                    <button type="button" class="btn" id="btn-place">Add Objects</button>
                    <button type="button" class="btn active" id="btn-preview">Preview</button>
                </div>
                <p id="infopanel">
                    Step forward/back - Up/Down :: Step left/right - A/D :: Look left/right - Left/Right :: Look up/down - PgUp/PgDown
                </p>
            </div>
        </div>
    </div><!--/.well -->
</div>
<div class="span9">
    <h1>Build your Gallery</h1>
    <div id="main" class="clearfix">
        <canvas id="lesson10-canvas" style="border: none;" width="750" height="400"></canvas>
            <div id="loadingtext">Loading gallery...</div>
            <div id="debugfeedback" class="hide"></div>
    </div>
    <div class="row-fluid">
        <div class="span12">
            <?php
            //echo '<pre>';
            //$this->session->collection = array();
            foreach ($this->session->collection as $item) {

                echo '<div class="span1">' .
                '<a href="#"><img rel="popover" title="' . $item['title'] . '" data-content="' . $item['description'] . '" ' .
                "src='http://social.apps.lv/image.php?h=100&zc=3&src=" . urlencode($item['europeana:object']) . "' ".
                " data-img='".base64_encode("http://social.apps.lv/image.php?w=100&zc=3&src=".urlencode($item['europeana:object']))."' />" .
                '</a></div>';
            }
            ?>
        </div>
    </div>
</div>

