<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Word Generator</title>

    <script src=https://cdn.jsdelivr.net/npm/pretty-print-json@0.2/dist/pretty-print-json.js> </script> <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.4/clipboard.min.js"></script>

    <script type="text/javascript">
        // jquery kullanarak interaktif tasarım yapılacaktır.
        $(document).ready(function() {

            $("#get-started-btn").on("click", function() {
                const topic = $("#topic").val();
                const length = $("#words-length").val();

                let header = {
                    topic: topic,
                    length: length,
                }

                $(".generator-form").addClass("d-none")
                $(".generator-word-form").removeClass("d-none")
                $(".generator-word-form").addClass("d-block")

                var i;
                var text = "";
                for (i = 1; i <= length; i++) {
                    text += '<div class="card mb-3"><div class="card-body"><div class="card-title">Word ' + i + '</div><input  class="form-control" type="text" placeholder="Enter a word" id="word-' + i + '" name="word-' + i + '" /> <input class="form-control mt-1" type="text" placeholder="Enter 1st desc"  id="desc-' + i + '-1" name="desc-' + i + '-1" /><input class="form-control mt-1" type="text" placeholder="Enter 2nd desc"  id="desc-' + i + '-2" name="desc-' + i + '-2" /><input class="form-control mt-1" type="text" placeholder="Enter 3th desc"  id="desc-' + i + '-3" name="desc-' + i + '-3" /></div></div></div>'
                }

                if (length <= 0) {
                    var alert = '<div class="alert alert-danger" role="alert">Please enter words count</div>';
                    $(".words-input").html(alert);
                } else {
                    $(".words-input").html(text);
                }



            });

            const values = [];

            $("#finished-btn").on('click', function() {
                $('input').each(
                    function(index) {
                        var input = $(this);

                        values.push({
                            name: input.attr('name'),
                            value: input.val()
                        })
                        $("#code_review").html(null);

                        $.post("control.php", values, function(result) {

                            function output(inp) {
                                document.body.appendChild(document.createElement('pre')).innerHTML = inp;
                            }

                            function syntaxHighlight(json) {
                                json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                                return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function(match) {
                                    var cls = 'number';
                                    if (/^"/.test(match)) {
                                        if (/:$/.test(match)) {
                                            cls = 'key';
                                        } else {
                                            cls = 'string';
                                        }
                                    } else if (/true|false/.test(match)) {
                                        cls = 'boolean';
                                    } else if (/null/.test(match)) {
                                        cls = 'null';
                                    }
                                    return '<span class="' + cls + '">' + match + '</span>';
                                });
                            }

                            $("#code_review").html(syntaxHighlight(result));

                        });

                    }

                );



            })



            function CopyToClipboard(containerid) {
                var textarea = document.createElement('textarea')
                textarea.id = 'temp_element'
                textarea.style.height = 0
                document.body.appendChild(textarea)
                textarea.value = document.getElementById(containerid).innerText
                var selector = document.querySelector('#temp_element')
                selector.select()
                document.execCommand('copy')
                document.body.removeChild(textarea)
                alert("Code is copied!")
            }


            $("#copy").on('click', function() {
                CopyToClipboard("code_review")
            });


            $("#back-btn").on('click', function() {
                $(".generator-form").removeClass("d-none")
                $(".generator-word-form").addClass("d-none")
                $(".generator-word-form").removeClass("d-block")

            });

            // $("#get-last-btn").on('click', function() {
            //     $.post("control.php", {
            //         getlastdata: true
            //     }, function(result) {
            //         if (result === "error: bos") {
            //             var alert = '<div class="alert alert-danger" role="alert">Son geçmiş veriniz bulunmuyor!</div>';
            //             $("#code_review").html(alert);
            //         }else {
            //             $("#code_review").html(result);
            //         }
            //     });
            // })

        });
    </script>

    <style>
        pre {
            outline: 1px solid #ccc;
            padding: 5px;
            margin: 5px;
        }

        .string {
            color: green;
        }

        .number {
            color: darkorange;
        }

        .boolean {
            color: blue;
        }

        .null {
            color: magenta;
        }

        .key {
            color: red;
        }
    </style>

</head>

<body>
    <nav class="navbar navbar-light bg-light">
        <span class="navbar-brand mb-0 h1">Word Generator</span>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md p-2">
                <!-- generator panel -->
                <div class="card generator-form">
                    <div class="card-body">
                        <h5 class="card-title">Generator Form</h5>
                        <form name="get-started" method="post">
                            <div class="form-group">
                                <label for="words-length">Words' Count</label>
                                <input type="text" id="words-length" class="form-control" name="words-length" placeholder="20">
                            </div>
                            <div class="form-group">
                                <label for="topics">General Topics of Words</label>
                                <input type="text" id="topic" class="form-control" name="topic" placeholder="Economics">
                            </div>
                            <!-- <div class="form-group">
                                <button type="button" id="get-last-btn" class="btn btn-danger float-left">Get Last Data</button>
                            </div> -->
                            <div class="form-group">
                                <button type="button" id="get-started-btn" class="btn btn-primary float-right">Skip to 2nd section</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card generator-word-form d-none">

                    <div class="card-body">
                        <button type="button" id="back-btn" class="btn btn-primary">Back First Info</button>
                    </div>
                    <div class="card-body pb-0">
                        <div class="words-input"></div>
                    </div>
                    <div class="card-body">
                        <button type="button" id="finished-btn" class="btn btn-primary">Send words</button>
                    </div>
                    <div>

                    </div>
                </div>


            </div>
            <div class="col-md p-2">
                <div class="card">
                    <div class="card-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <h5>JSON Review</h5>
                                </div>
                                <div class="col">
                                    <button type="button" id="copy" class="btn btn-success btn-sm float-right">Copy to clipboard</button>
                                </div>
                            </div>
                        </div>
                        <div id="code_review" class="p-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col text-center fixed-bottom">
        <h6>made by
            <a class="btn btn-dark btn-sm " data-toggle="collapse" href="http://oguzydz.com" role="button" aria-expanded="false" aria-controls="collapseExample">
                @oguzydz
            </a>
        </h6>
    </div>
</body>

</html>