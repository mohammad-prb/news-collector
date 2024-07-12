<div class="itemKhabar">
    <a class="linkAks" href="<?php echo $row["href"]?>">
        <img class="aksKhabar" src="<?php echo $row["image"]; ?>" alt="<?php echo $row["alt"]; ?>"/>
    </a>
    <div class="kadrMatnKhabar">
        <a class="linkTitr" href="<?php echo $row["href"]; ?>"><?php echo $row["title"]; ?></a>
        <p class="description"><?php echo $row["description"]; ?></p>
        <a class="etelaatKhabar" href="https://www.<?php echo $row["website"]; ?>"><?php echo $row["website"]; ?></a>
    </div>
</div>
