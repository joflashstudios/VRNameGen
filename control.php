<?    
	include("database.inc");
    $uuid = uniqid();
    $type = $_REQUEST["type"];
?>

<div id="<?=$uuid?>" class="control">
    <div>
        <strong><?=$type?></strong>
        <button type="button" class="delete btn btn-link">X</button>
        <? if ($type != "Static") { ?>
            <button type="button" class="refresh btn btn-link"><img src="/static/refresh.png" width="16" /></button>
        <? } ?>
        <button type="button" class="move fwd btn btn-link">></button>
        <button type="button" class="move bck btn btn-link"><</button>
    </div>    
    <? if ($type != "Static") { ?>
        <canvas height="66" width="240"></canvas>    
    <? } else { ?>
        <input type="text"/>
    <? } ?>
    <div>                
        <? if ($type != "Static") { ?>
            <select multiple="multiple">
				<? foreach (getCategories() as $category) { ?>
                    <option selected><?=$category?></option>
                <? } ?>
            </select>        
        <? } ?>
    </div>
    <script type="text/javascript">
        function getRandomIntInclusive(min, max) {
            return Math.floor(Math.random() * (max - min + 1)) + min;
        }

        function setup<?=$uuid?>() {
            var term = new Term(document.getElementById("<?=$uuid?>"), "<?=$type?>");
            term.register();

            $(term.containerObject).find("button.delete").click(function () {
                term.delete();
            });

            $(term.containerObject).find("button.refresh").click(function () {
                term.roll();
            });

            $(term.containerObject).find("button.fwd").click(function () {
                var current = $(this).parent().parent();
                var lastWasMe = false;
                var cancel = false;
                $(current).parent().children().each(function () {
                    if (!cancel)
                    {
                        if (lastWasMe)
                        {
                            current.insertAfter($(this));
                            cancel = true;
                        }
                        if (current.is($(this)))
                        {
                            lastWasMe = true;
                        }
                    }
                });
            });

            $(term.containerObject).find("button.bck").click(function () {
                var current = $(this).parent().parent();
                var last = null;
                var cancel = false;
                $(current).parent().children().each(function () {
                    if (!cancel)
                    {
                        if (current.is($(this)))
                        {
                            current.insertBefore(last);
                            cancel = true;
                        }
                        last = $(this);
                    }
                });
            });

            $(term.containerObject).find("select[multiple='multiple']").multiselect(
                {nonSelectedText: 'Choose categories:',
                allSelectedText: 'All Categories',
                numberDisplayed: 3,
                nSelectedText: 'Categories'}
            );

            var rollCanvas = function (values) {
                var canvas = $("#<?=$uuid?> canvas");
                var selection = values[getRandomIntInclusive(0, values.length - 1)];
                if (!values || values.length == 0)
                    selection = "no results";

                renderCanvas(canvas, values);
            }

            term.roll = function () {
                var categories = $(term.containerObject).find("select[multiple='multiple']").val();
                if (categories != null)
                {
                    var crazyCats = "";

                    for (i = 0; i < categories.length; i++) {
                        crazyCats += categories[i];
                        if (i != categories.length)
                            crazyCats += ",";
                    }
                    
                    $.getJSON( "getTerms.php?type=" + term.type + "&cats=" + crazyCats, function( data ) {
                        rollCanvas(data);
                    });
                }
            }

            term.roll(); //Get an initial value they can fiddle with
        }
        setup<?=$uuid?>();
        //# sourceURL=dynamicScript<?=$uuid?>.js
    </script>
</div>
