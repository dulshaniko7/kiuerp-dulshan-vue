var uiText = '<div class="clearfix text-center">';

    <?php
    if(isset($states) && is_array($states) && count($states)>0)
    {
        foreach($states as $state)
        {
            $label="info";
            if(isset($state["label"]))
            {
                $label=$state["label"];
            }
            ?>
            if(data == "<?php echo $state["id"]; ?>")
            {
            uiText += '<span class="btn btn-<?php echo $label; ?> btn-sm"><?php echo $state["name"]; ?></span>';
            }
            <?php
        }
    }
    ?>

    uiText += '</div>';
return uiText;
