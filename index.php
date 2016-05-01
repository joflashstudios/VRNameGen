<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <title>VR Name Generator</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        var state = Object();
        state.termIndex = 1;
        state.Terms = {};

        state.addTerm = function (term) {
            state.termIndex++;
            term.index = state.termIndex;
            state.Terms[term.index] = term;
        }

        state.deleteTerm = function (term) {
            delete state.Terms[term.index];
        }

        state.loadTerm = function loadTerm(type, callback) {
            $("<div>").load("control.php?type=" + type, function () {
                $(this).find(".control").insertBefore("#termHolder .add");
                if (callback)
                    callback();
            });
        }

        var Term = function (containerObject, type) {
            this.containerObject = containerObject;
            this.type = type;
            this.index = state.termIndex;
            state.termIndex++;
        }

        Term.prototype.delete = function () {
            state.deleteTerm(this);
            $(this.containerObject).remove();
        }

        Term.prototype.register = function () {
            state.addTerm(this);
        }

        function reroll() {
            for (var element in state.Terms) {
                state.Terms[element].roll();
            }
        }

        function renderCanvas(canvas, values) {
            var width = canvas.width();
            var height = canvas.height();
            var canvasP = canvas[0];
            var ctx = canvasP.getContext("2d");
            ctx.font = "64px Sling";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";
            ctx.fillStyle = "#eeeeee";

            ctx.clearRect(0, 0, width, height);

            //ctx.fillText(selection, width / 2, height / 2, width);
            var data = new Object();
            for (var i = 0; i < values.length; i++) {
                data[i] = new Object();
                data[i].text = values[i];
                data[i].y = (height / 2) - (height * (i)); //Start with everything off-screen.
                data[i].wrapCount = 0;
            }

            function draw() {
                ctx.clearRect(0, 0, width, height);

                var lastY = 0;
                var lowestWrapCount = 500;

                for (i in data) {
                    data[i].y += 10;

                    if (data[i].y > (height / 2) + (height * (Object.keys(data).length - 1.5))) {
                        data[i].wrapCount++;
                        data[i].y = -height;
                    }

                    lastY = data[i].y;

                    if (data[i].wrapCount < lowestWrapCount)
                        lowestWrapCount = data[i].wrapCount;

                    ctx.fillText(data[i].text, width / 2, data[i].y, width);
                }

                if ((lastY <= height / 2 || lowestWrapCount < 1) && Object.keys(data).length > 1)
                    requestAnimationFrame(draw);
                else {
                    ctx.clearRect(0, 0, width, height);
                    ctx.fillText(data[Object.keys(data).length - 1].text, width / 2, height / 2, width);
                }
            }
            window.requestAnimationFrame(draw);
        }

        $(document).ready(function () {
            state.loadTerm("Description", function () {
                state.loadTerm("Location", function () {
                    state.loadTerm("Property");
                });
            });
        });
    </script>
    <link rel="stylesheet" href="/static/app.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script type="text/javascript" src="/static/bootstrap-multiselect.js"></script>
    <link rel="stylesheet" href="/static/bootstrap-multiselect.css" type="text/css" />
</head>
<body>
    <div class="container">
        <div class="header leftbox">
            <h1>Vacation Rental Nicknames</h1>
            <h2>Find the perfect name for your vacation home!</h2>
        </div>
        <div id="contentHolder">
            <div id="termHolder">
                <div class="add control">
                    <div class="dropdown">

                        <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
                            Add
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" onclick="state.loadTerm('Property')">Property</a></li>
                            <li><a href="#" onclick="state.loadTerm('Description')">Description</a></li>
                            <li><a href="#" onclick="state.loadTerm('Location')">Location</a></li>
                            <li><a href="#" onclick="state.loadTerm('Static')">Static Text</a></li>
                        </ul>
                    </div>
                    <img src="/static/refresh.png" onclick="reroll();" />
                </div>
            </div>
        </div>
        <hr />
        <div>
            <link href="//cdn-images.mailchimp.com/embedcode/classic-10_7.css" rel="stylesheet" type="text/css">
            <style type="text/css">
                #mc_embed_signup {
                    clear: left;
                    font: 14px Helvetica,Arial,sans-serif;
                    max-width: 400px;
                    margin-bottom: 120px;
                }

                    #mc_embed_signup input {
                        color: #222;
                    }
                /* Add your own MailChimp form style overrides in your site stylesheet or in this style block.
                We recommend moving this block and the preceding CSS link to the HEAD of your HTML file. */
            </style>
            <div id="mc_embed_signup" class="leftbox">
                <form action="//VacationRentalTechnology.us12.list-manage.com/subscribe/post?u=6f369a2ce3bd4d22d638716de&amp;id=a7e0bf6506" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                    <div id="mc_embed_signup_scroll">
                        <h2>Want more great VR tools? Subscribe to our mailing list:</h2>
                        <div class="indicates-required"><span class="asterisk">*</span> indicates required</div>
                        <div class="mc-field-group">
                            <label for="mce-EMAIL">
                                Email Address  <span class="asterisk">*</span>
                            </label>
                            <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                        </div>
                        <div class="mc-field-group">
                            <label for="mce-FNAME">First Name </label>
                            <input type="text" value="" name="FNAME" class="" id="mce-FNAME">
                        </div>
                        <div class="mc-field-group">
                            <label for="mce-LNAME">Last Name </label>
                            <input type="text" value="" name="LNAME" class="" id="mce-LNAME">
                        </div>
                        <div id="mce-responses" class="clear">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
                        </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_6f369a2ce3bd4d22d638716de_a7e0bf6506" tabindex="-1" value=""></div>
                        <div class="clear"><input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
                    </div>
                    <input type="checkbox" value="1" id="group_16" name="group[4997][16]" class="av-checkbox" checked style="display: none;">
                </form>
            </div>
            <script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script>
            <script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';}(jQuery));var $mcj = jQuery.noConflict(true);</script>
        </div>
    </div>
    <div class="VRT">
        <a href="http://vacationrentaltechnology.com"><img src="/static/VRT_logo_03.png" /></a>
        <a href="http://vacationrentaltechnology.com"><strong>Vacation Rental Technology</strong></a>
        <p>
            If you are loving the site, imagine what else Vacation Rental Technologies can do for you!<br />
            <a href="http://vacationrentaltechnology.com">Click here</a> to get some entertaining and informative videos, and other free stuff to help you learn more about how to create a dynamic vacation rental business.
        </p>
    </div>


</body>
</html>