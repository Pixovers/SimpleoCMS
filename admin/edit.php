<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>edit</title>
    <script src="https://kit.fontawesome.com/f458f3684b.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="src/style.css">

    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css' rel='stylesheet'>

    <style>
        #editor {
          min-height: 600px;
    
          width: auto;
          margin: 0px auto 0;
          padding: 10px;
        }
    
        [contentEditable=true]:empty:not(:focus):before {
          content: attr(data-text);
          color: #888;
        }
    
        [contenteditable] {
          outline: 0px solid transparent;
        }
      </style>

    <style>
        .stretch-card>.card {
            width: 100%;
            min-width: 100%
        }

        body {
            background-color: #f9f9fa
        }

        .flex {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto
        }

        @media (max-width:991.98px) {
            .padding {
                padding: 1.5rem
            }
        }

        @media (max-width:767.98px) {
            .padding {
                padding: 1rem
            }
        }

        .padding {
            padding: 3rem !important
        }

        .card-sub {
            cursor: move;
            border: none;
            -webkit-box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05), 0 -2px 1px -2px rgba(0, 0, 0, 0.04), 0 0 0 -1px rgba(0, 0, 0, 0.05);
            box-shadow: 0 0 1px 2px rgba(0, 0, 0, 0.05), 0 -2px 1px -2px rgba(0, 0, 0, 0.04), 0 0 0 -1px rgba(0, 0, 0, 0.05)
        }

        .card-img-top {
            width: 100%;
            border-top-left-radius: calc(.25rem - 1px);
            border-top-right-radius: calc(.25rem - 1px)
        }

        .card-block {
            padding: 1.25rem;
            background-color: #fff !important
        }

        .sortable-moves {
            cursor: move;
            margin-bottom: 0;
            -webkit-box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.14);
            box-shadow: 0 1px 5px 0 rgba(0, 0, 0, 0.14);
            margin-bottom: 20px;
            padding: 10px 10px 10px 10px;
        }

        .sortable-moves {
            font-size: 14px;
            line-height: 1.55556em;
            list-style-type: none;
            margin-bottom: 15px;
            min-height: 3.55556em;
            position: relative;
            cursor: move;
            background-color: #fff
        }

        .sortable-moves img {
            position: absolute;
            height: 50px;
            width: 50px;
            left: 10px;
            border-radius: 5px;
            top: 15px
        }
    </style>

    <style>
        .btn-file {
            position: relative;
            overflow: hidden;
        }

        .btn-file input[type=file] {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 100%;
            min-height: 100%;
            font-size: 100px;
            text-align: right;
            filter: alpha(opacity=0);
            opacity: 0;
            outline: none;
            background: white;
            cursor: inherit;
            display: block;
        }

        #img-upload {
            width: 100%;
        }
    </style>
    <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script type='text/javascript'
        src='https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js'></script>
    <script type='text/javascript'>$(document).ready(function () {
            $("#sortable").sortable();
            $("#sortable").disableSelection();
        });</script>
    <script type='text/javascript'>$(document).ready(function () {
            $("#sortable1").sortable();
            $("#sortable1").disableSelection();
        });</script>
</head>

<body oncontextmenu='return false' class='snippet-body'>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


    <div class="container-fluid ">
        <h1 class="">Modifica Articolo</h1>

        <div class="row mt-2">

            <div class="col-md-9 " >
              
                
                <div class="card shadow-lg mt-3">
                    <div class="card-header py-0 ">
                      <div class="row mt-3">
                        <div class="btn-toolbar" role="toolbar">
                          <div class="btn-group mr-2 py-1">
                            <button type="button" class="btn btn-primary" onclick="editorCommand('bold');" data-toggle="tooltip"
                              title="Bold"><i class="fas sm fa-bold"></i></button>
                            <button type="button" class="btn btn-primary" onclick="editorCommand('italic');" data-toggle="tooltip"
                              title="Italic"><i class="fas fa-italic"></i></button>
                            <button type="button" class="btn btn-primary" onclick="editorCommand('strikethrough');"
                              data-toggle="tooltip" title="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                            <button type="button" class="btn btn-primary" onclick="editorCommand('underline');"
                              data-toggle="tooltip" title="Underline"><i class="fas fa-underline"></i></button>
                          </div>
          
                          <div class="btn-group mr-2 py-1" role="group">
                            <button type="button" class="btn btn-secondary" onclick="editorCommand('justifyleft');"
                              data-toggle="tooltip" title="Aligh Left"><i class="fas fa-align-left"></i></button>
                            <button type="button" class="btn btn-secondary" onclick="editorCommand('justifycenter');"
                              data-toggle="tooltip" title="Align Center"><i class="fas fa-align-center"></i></button>
                            <button type="button" class="btn btn-secondary" onclick="editorCommand('justifyright');"
                              data-toggle="tooltip" title="Align Right"><i class="fas fa-align-right"></i></button>
                            <button type="button" class="btn btn-secondary" onclick="editorCommand('justifyfull');"
                              data-toggle="tooltip" title="Align Justify"><i class="fas fa-align-justify"></i></button>
                          </div>
          
          
          
                          <div class="btn-group mr-2 py-1" role="group">
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#link"
                              onclick="saveSelection();" data-toggle="tooltip" title="Link"><i class="fas fa-link"></i></button>
                            <button type="button" class="btn btn-warning" onclick="editorCommand('unlink');" data-toggle="tooltip"
                              title="Unlink"><i class="fas fa-unlink"></i></button>
                          </div>
                          <div class="btn-group mr-2 py-1" role="group">
                            <button type="button" class="btn btn-success" onclick="editorCommand('undo');" data-toggle="tooltip"
                              title="Undo"><i class="fas fa-undo"></i></button>
                            <button type="button" class="btn btn-success" onclick="editorCommand('redo');" data-toggle="tooltip"
                              title="Redo"><i class="fas fa-redo"></i></button>
                          </div>
                          <div class="btn-group mr-2 py-1" role="group">
                            <button type="button" class="btn btn-danger" onclick="editorCommand('cut');" data-toggle="tooltip"
                              title="Cut"><i class="fas fa-cut"></i></button>
                            <button type="button" class="btn btn-danger" onclick="editorCommand('copy');" data-toggle="tooltip"
                              title="Copy"><i class="fas fa-copy"></i></button>
                            <button type="button" class="btn btn-danger" onclick="editorCommand('paste');" data-toggle="tooltip"
                              title="Paste"><i class="fas fa-paste"></i></button>
                          </div>
                          <div class="btn-group mr-2 py-1" role="group">
                            <button type="button" class="btn btn-info" onclick="editorCommand('justifyfull');"
                              data-toggle="tooltip" title="Color text"><i class="fas fa-font"></i></button>
          
                          </div>
                          <div class="btn-group  mr-2 py-1" role="group">
                            <button type="button" class="btn btn-info" onclick="editorCommand('insertunorderedlist');"
                              data-toggle="tooltip" title="Dotted list"><i class="fas fa-list-ul"></i></button>
                            <button type="button" class="btn btn-info" onclick="editorCommand('insertorderedlist');"
                              data-toggle="tooltip" title="Numbered text"><i class="fas fa-list-ol"></i></button>
                          </div>
                          <div class="btn-group py-1" role="group">
                            <button type="button" class="btn btn-dark" onclick="editorCommand('justifyfull');"
                              data-toggle="tooltip" title="Dotted list"> <i class="fas fa-images"></i></button>
                            <button type="button" class="btn btn-dark" onclick="editorCommand('justifyfull');"
                              data-toggle="tooltip" title="Dotted list"><i class="fas fa-film"></i></button>
          
                            </button>
                          </div>
                        </div>
                      </div>
          
          
                      <div class="row">
          
          
          
                        <div class="btn-toolbar" role="toolbar">
                          <div class="btn-group mr-2 py-1" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle"
                              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Georgia
                            </button>
                            <div class="dropdown-menu " aria-labelledby="btnGroupDrop1">
                              <a class="dropdown-item" href="#">Georgia</a>
                              <a class="dropdown-item" href="#">Verdana</a>
                            </div>
                          </div>
                          <div class="btn-group mr-2 py-1" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-secondary dropdown-toggle"
                              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Font size
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                              <a class="dropdown-item" href="#">1</a>
                              <a class="dropdown-item" href="#">2</a>
                              <a class="dropdown-item" href="#">3</a>
                              <a class="dropdown-item" href="#">4</a>
                            </div>
                          </div>
          
                          <div class="btn-group mr-2 py-1" role="group">
                            <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-danger dropdown-toggle"
                              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Formatting
                            </button>
                            <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                              <a class="dropdown-item" href="#">Paragraph</a>
                              <a class="dropdown-item" href="#">
                                <h1>Heading 1</h1>
                              </a>
                              <a class="dropdown-item" href="#">
                                <h2>Heading 2</h2>
                              </a>
                              <a class="dropdown-item" href="#">
                                <h3>Heading 3</h3>
                              </a>
                              <a class="dropdown-item" href="#">
                                <h4>Heading 4</h4>
                              </a>
                              <a class="dropdown-item" href="#">
                                <h5>Heading 5</h5>
                              </a>
                              <a class="dropdown-item" href="#">
                                <h6>Heading 6</h6>
                              </a>
          
          
                            </div>
                          </div>
          
          
                          <div class="btn-group py-1" role="group">
                            <button type="button" class="btn  btn-dark" data-toggle="tooltip" title="Dotted list"><i
                                class="fas fa-eraser"></i></button>
          
                            </button>
                          </div>
                        </div>
                      </div>
          
                    </div>
                    <div class="card-body p-1">
                      <div class="row">
                        <div class="col-12">
          
                          <div id="editor" contenteditable="true" data-text="Ciao pino.."></div>
          
          
                        </div>
                      </div>
                    </div>
          
          
                    <div class="card-footer py-0">
                      <div class="btn-toolbar" role="toolbar">
                        <div class="btn-group mr-2 py-1">
                          <button type="button" class="btn btn-sm btn-primary" data-toggle="tooltip" title="Bold"><i
                              class="fas fa-code"></i></button>
                        </div>
          
          
                      </div>
          
                    </div>
          
                  </div>

<div  id="sortable1" > 
                  <div class="card bg-light mb-3 shadow-lg mt-3" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>

                  <div class="card bg-light mb-3 shadow-lg" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>

                  <div class="card bg-light mb-3 shadow-lg" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>

                  <div class="card bg-light mb-3 shadow-lg" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>

                  <div class="card bg-light mb-3 shadow-lg" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>

                  <div class="card bg-light mb-3 shadow-lg" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>


                  <div class="card bg-light mb-3 shadow-lg" >
                    <div class="card-header">Header</div>
                    <div class="card-body">
                      <h5 class="card-title">Light card title</h5>
                      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                  </div>
                </div>
            </div>





            <div class="col-md-3 fixed-top offset-md-9 mt-4">
                <!-- Draggable Multiple List card start -->

                <div class="row">
                    <div class="col-md-12" id="sortable">

                        <div class="sortable-moves" style="">

                            <h6>languages</h6>

                            <div class="form-group">
                                <select class="form-control" id="exampleFormControlSelect1">
                                    <option>inglese</option>
                                    <option>italiano</option>
                                    <option>francese</option>
                                </select>
                            </div>
                        </div>

                        <div class="sortable-moves" draggable="false" style="">
                            <h6>Pubblica</h6>
                            <button type="button" class="btn btn-primary">Pubblica</button>
                            <button type="button" class="btn btn-secondary">Salva</button>
                        </div>

                        <div class="sortable-moves" draggable="false" style="">
                            <h6>Categorie</h6>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">boa</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">vimbing</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">cvp</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">tim</label>
                            </div>

                        </div>

                        <div class="sortable-moves" draggable="false" style="">
                            <h6>tag</h6>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">900</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">pppppppp</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">bic</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input position-static" type="checkbox" id="blankCheckbox"
                                    value="option1" aria-label="">
                                <label class="form-check-label" for="inlineRadio1">fulmini in casa</label>
                            </div>

                        </div>


                        <div class="sortable-moves" style="">



                            <h6>images</h6>

                            <div class="form-group">
                                <label>Upload Image</label>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <span class="btn btn-default btn-file">
                                            Browse… <input type="file" id="imgInp">
                                        </span>
                                    </span>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                <img id='img-upload' />
                            </div>

                        </div>




                    </div>
                </div>
            </div> <!-- Draggable Multiple List card end -->


        </div>


    </div>

    <script>

    </script>

    <script>
        $(document).ready(function () {
            $(document).on('change', '.btn-file :file', function () {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function (event, label) {

                var input = $(this).parents('.input-group').find(':text'),
                    log = label;

                if (input.length) {
                    input.val(log);
                } else {
                    if (log) alert(log);
                }

            });
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function () {
                readURL(this);
            });
        });
    </script>


<script type="text/javascript">
    var editor;                   //editor object
    var editorText;               //editor text
    var currentSelectionRange;    //current selection

    //prepare editor
    function initEditor() {
      editor = document.getElementById("editor");
      editorText = editor.innerHTML;
    }

    //execute command
    function editorCommand(command, value) {
      document.execCommand(command, false, value);
      editor.focus();
    }

    //insert link value
    function insertLink(value) {
      editorCommand("createLink", value);
      document.getElementById('inputLink').value = "";
    }

    //save current selection (because of focus changing for example)
    function saveSelection() {
      currentSelectionRange = document.getSelection().getRangeAt(0);
    }

    //reset selection, based on saved selection
    function resetSelection() {
      var selection = window.getSelection();
      selection.removeAllRanges();
      selection.addRange(currentSelectionRange);
    }

  </script>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx"
        crossorigin="anonymous"></script>
    <script>
        //tooltips
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })

    </script>
</body>

</html>