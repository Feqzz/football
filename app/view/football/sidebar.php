<div class="col-3">
    <div class="bg-light border-0" id="sidebar-wrapper">
        <div class="list-group list-group-flush">
            <a href="overview" id="overview" class="list-group-item list-group-item-action ">Overview</a>
            <a href="fixtures" id="fixtures" class="list-group-item list-group-item-action ">Fixtures</a>
            <a href="leaderboard" id="leaderboard" class="list-group-item list-group-item-action ">Leaderboard</a>
        </div>
    </div>
</div>
<script>
    var page_name = "<?php echo isset($this->page_title['name']) ? $this->page_title['name'] : null;?>";
    if (page_name)
    {
        var a = document.getElementById(page_name);
        a.setAttribute("style","font-weight: bold");
    }
</script>
