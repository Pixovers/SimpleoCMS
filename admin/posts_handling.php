<?php

include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/category.php";
include_once $_SERVER['DOCUMENT_ROOT'] . "/../src/model/post.php";

if (isset($_ACTION)) {
    switch ($_ACTION) {
        case "new":
            //echo var_dump($_POST);
            if (isset($_GET['lang'])) {
                $language = Language::byCode($_CONN, $_GET['lang']);
            } else {
                $language = Language::getDefaultLanguage($_CONN);
            }

            if (isset($_POST['title'])) {

                if (isset($_POST['content'])) {
                    $content = $_POST['content'];
                } else {
                    $content = "";
                }

                if (isset($_POST['meta_title'])) {
                    $meta_title = $_POST['meta_title'];
                } else {
                    $meta_title = "";
                }

                if (isset($_POST['meta_description'])) {
                    $meta_description = $_POST['meta_description'];
                } else {
                    $meta_description = "";
                }


                Post::addNew(
                    $_CONN,
                    $_POST['title'],
                    "url",
                    $content,
                    $_POST['cat_id'],
                    1,
                    $language,
                    NULL,
                    $meta_title,
                    $meta_description
                );

                header("location: /admin/posts");
                exit();
            }
            break;

        case "edit":
            if (isset($_GET['id'])) {
                $post = Post::byId($_CONN, $_GET['id']);

                if ($post) {

                    $language = $post->getLang();

                    if (isset($_POST['title'])) {
                        if (isset($_POST['content'])) {
                            $content = $_POST['content'];
                        } else {
                            $content = "";
                        }

                        if (isset($_POST['meta_title'])) {
                            $meta_title = $_POST['meta_title'];
                        } else {
                            $meta_title = "";
                        }

                        if (isset($_POST['meta_description'])) {
                            $meta_description = $_POST['meta_description'];
                        } else {
                            $meta_description = "";
                        }

                        $post->setName($_POST['title']);
                        $post->setUrl("url");
                        $post->setContent($content);
                        $post->setCategoryId($_POST['cat_id']);
                        $post->setMetaTitle($meta_title);
                        $post->setMetaDescription($meta_description);
                        $post->update($_CONN);
                    }
                } else {
                    Utils::error_404();
                }
            }
            break;

        case "delete":

            break;

        default:
            Utils::error_404();
    }
} else {
    Utils::error_404();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "templates/head.php"; ?>


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
</head>


<body onload="initEditor();">
    <div class="wrapper">
        <?php include_once "templates/sidebar.php"; ?>

        <div id="content">

            <?php include_once "templates/navbar.php"; ?>

            <div class="container-fluid">
                <!--page content-->
                <div class="container-fluid fade-in">
                    <h1 class="mt-2">New post</h1>

                    <div class="row mt-2">

                        <div class="col-md-9 ">
                            <div class="form-group">
                                <input type="text" class="form-control" id="post_name_input" placeholder="Post title" value="<?php if ($_ACTION == "edit") echo $post->getName(); ?>">
                            </div>
                            <div class="card  mt-3">
                                <div class="card-header py-0 ">
                                    <div class="row mt-3">
                                        <div class="btn-toolbar" role="toolbar">
                                            <div class="btn-group mr-2 py-1">
                                                <button type="button" class="btn btn-primary" onclick="editorCommand('bold');" data-toggle="tooltip" title="Bold"><i class="fas sm fa-bold"></i></button>
                                                <button type="button" class="btn btn-primary" onclick="editorCommand('italic');" data-toggle="tooltip" title="Italic"><i class="fas fa-italic"></i></button>
                                                <button type="button" class="btn btn-primary" onclick="editorCommand('strikethrough');" data-toggle="tooltip" title="Strikethrough"><i class="fas fa-strikethrough"></i></button>
                                                <button type="button" class="btn btn-primary" onclick="editorCommand('underline');" data-toggle="tooltip" title="Underline"><i class="fas fa-underline"></i></button>
                                            </div>

                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button type="button" class="btn btn-secondary" onclick="editorCommand('justifyleft');" data-toggle="tooltip" title="Aligh Left"><i class="fas fa-align-left"></i></button>
                                                <button type="button" class="btn btn-secondary" onclick="editorCommand('justifycenter');" data-toggle="tooltip" title="Align Center"><i class="fas fa-align-center"></i></button>
                                                <button type="button" class="btn btn-secondary" onclick="editorCommand('justifyright');" data-toggle="tooltip" title="Align Right"><i class="fas fa-align-right"></i></button>
                                                <button type="button" class="btn btn-secondary" onclick="editorCommand('justifyfull');" data-toggle="tooltip" title="Align Justify"><i class="fas fa-align-justify"></i></button>
                                            </div>



                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#link" onclick="saveSelection();" data-toggle="tooltip" title="Link"><i class="fas fa-link"></i></button>
                                                <button type="button" class="btn btn-warning" onclick="editorCommand('unlink');" data-toggle="tooltip" title="Unlink"><i class="fas fa-unlink"></i></button>
                                            </div>
                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button type="button" class="btn btn-success" onclick="editorCommand('undo');" data-toggle="tooltip" title="Undo"><i class="fas fa-undo"></i></button>
                                                <button type="button" class="btn btn-success" onclick="editorCommand('redo');" data-toggle="tooltip" title="Redo"><i class="fas fa-redo"></i></button>
                                            </div>
                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button type="button" class="btn btn-danger" onclick="editorCommand('cut');" data-toggle="tooltip" title="Cut"><i class="fas fa-cut"></i></button>
                                                <button type="button" class="btn btn-danger" onclick="editorCommand('copy');" data-toggle="tooltip" title="Copy"><i class="fas fa-copy"></i></button>
                                                <button type="button" class="btn btn-danger" onclick="editor.focus();execCommand('paste');" data-toggle="tooltip" title="Paste"><i class="fas fa-paste"></i></button>
                                            </div>
                                            <div class="input-group mr-2 py-1">
                                            </div>
                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button type="color" class="btn btn-info" data-toggle="tooltip" title="Color text">

                                                    <i class="fas fa-font"></i>
                                                    <input id="colorPicker" type="color" class="btn " onchange="editorCommand('foreColor',document.getElementById('colorPicker').value);">

                                                </button>
                                            </div>
                                            <div class="btn-group  mr-2 py-1" role="group">
                                                <button type="button" class="btn btn-info" onclick="editorCommand('insertunorderedlist');" data-toggle="tooltip" title="Dotted list"><i class="fas fa-list-ul"></i></button>
                                                <button type="button" class="btn btn-info" onclick="editorCommand('insertorderedlist');" data-toggle="tooltip" title="Numbered text"><i class="fas fa-list-ol"></i></button>
                                            </div>
                                            <div class="btn-group py-1" role="group">
                                                <button type="button" class="btn btn-dark" onclick="saveSelection();" data-toggle="modal" data-target="#image" data-toggle="tooltip" title="Insert Image"> <i class="fas fa-images"></i></button>
                                                <button type="button" class="btn btn-dark" onclick="saveSelection();" data-toggle="modal" data-target="#video" data-toggle="tooltip" title="Insert Youtube video"><i class="fas fa-film"></i></button>

                                                </button>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="btn-toolbar" role="toolbar">
                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button id="fontDropdown" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="saveSelection();">
                                                    Font
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="fontDropdown">
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontname','Georgia');">Georgia</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontname','Verdana');">Verdana</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontname','Arial');">Arial</a>
                                                </div>
                                            </div>
                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button id="sizeDropdown" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="saveSelection();">
                                                    Font size
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="sizeDropdown">
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',1);">8</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',2);">10</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',3);">12</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',4);">14</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',5);">18</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',6);">24</a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('fontsize',7);">36</a>
                                                </div>
                                            </div>

                                            <div class="btn-group mr-2 py-1" role="group">
                                                <button id="formattingDropdown" type="button" class="btn btn-sm btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" onclick="saveSelection();">
                                                    Formatting
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="formattingDropdown">
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','h1');">
                                                        <h1>Heading 1</h1>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','h2');">
                                                        <h2>Heading 2</h2>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','h3');">
                                                        <h3>Heading 3</h3>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','h4');">
                                                        <h4>Heading 4</h4>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','h5');">
                                                        <h5>Heading 5</h5>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','h6');">
                                                        <h6>Heading 6</h6>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','p');">
                                                        <p>Paragraph</p>
                                                    </a>
                                                    <a class="dropdown-item" onclick="resetSelection();editorCommand('formatblock','pre');">
                                                        <pre>Preformatted</pre>
                                                    </a>
                                                </div>
                                            </div>


                                            <div class="btn-group py-1" role="group">
                                                <button type="button" class="btn  btn-dark" onclick="editorCommand('removeFormat');" data-toggle="tooltip" title="Remove formatting"><i class="fas fa-eraser"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <div class="row">
                                        <div class="col-12">

                                            <div id="editor" contenteditable="true" data-text="Write text here..."><?php if( $_ACTION == "edit" ) { echo $post->getContent(); } ?></div>

                                        </div>
                                    </div>
                                </div>


                                <div class="card-footer py-0">
                                    <div class="btn-toolbar" role="toolbar">
                                        <div class="btn-group btn-group-toggle mr-2 py-1" data-toggle="buttons">
                                            <label id="toggleModeButton" class="btn btn-primary">
                                                <input type="checkbox" checked autocomplete="off" onclick="toggleModeClicked();"><i class="fas fa-code"></i>
                                            </label>
                                        </div>


                                    </div>

                                </div>
                            </div>

                            <!-- Insert link -->
                            <div class="modal fade" id="link" tabindex="-1" aria-labelledby="link" aria-hidden="true" disabled>
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Insert Hyperlink</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">URL:</span>
                                                </div>
                                                <input id="inputLink" type="text" class="form-control" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="resetSelection();insertLink(document.getElementById('inputLink').value);" data-dismiss="modal">Insert</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Insert Image -->
                            <div class="modal fade" id="image" tabindex="-1" aria-labelledby="link" aria-hidden="true" disabled>
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Insert Image Url</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">IMAGE URL:</span>
                                                </div>
                                                <input id="inputImage" type="text" class="form-control" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="resetSelection();insertImage(document.getElementById('inputImage').value);" data-dismiss="modal">Insert</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Insert Video -->
                            <div class="modal fade" id="video" tabindex="-1" aria-labelledby="link" aria-hidden="true" disabled>
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Insert YouTube Video</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text" id="inputGroup-sizing-default">VIDEO URL:</span>
                                                </div>
                                                <input id="inputVideo" type="text" class="form-control" aria-describedby="inputGroup-sizing-default">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="resetSelection();insertVideo(document.getElementById('inputVideo').value);" data-dismiss="modal">Insert</button>
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="sortable1">




                                <div class="card bg-light mt-3 mb-3 shadow-sm ">
                                    <div class="card-header">Header</div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="metaTitleInput">Meta Title</label>
                                            <input type="text" class="form-control" id="metaTitleInput" value="<?php if ($_ACTION == "edit") echo $post->getMetaTitle(); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="SlugInput">URL slug</label>
                                            <input type="text" class="form-control" id="SlugInput" name="SlugInput" value="<?php if ($_ACTION == "edit") echo $post->getUrl(); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="metaDescriptionInput">Meta description</label>
                                            <textarea class="form-control" id="metaDescriptionInput" rows="3"><?php if ($_ACTION == "edit") echo $post->getMetaDescription(); ?></textarea>
                                        </div>
                                    </div>
                                </div>




                            </div>

                        </div>





                        <div class="col-md-3 ">

                            <div class="row mt-2">
                                <div class="col-md-12" id="sortable">



                                    <div class="card shadow-sm mt-2 mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title">Language:</h5>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="LangName" name="LangName" value="<?php echo $language->getLangName(); ?>" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card shadow-sm mt-2 mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title">Publish Post</h5>
                                            <button type="button" class="btn btn-primary" onclick="createPost();">Publish</button>
                                            <!--button type="button" class="btn btn-secondary">Salva</button-->
                                        </div>
                                    </div>

                                    <div class="card shadow-sm mt-2 mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title">Category</h5>
                                            <?php
                                            $categories = Category::fetchAllByLang($_CONN, $language);

                                            foreach ($categories as $cat) {
                                            ?>
                                                <div class="form-check">
                                                    <input class="form-check-input position-static" name="category" type="radio" <?php if ($_ACTION == "edit" && $cat->getId() == $post->getCategoryId() ) echo "checked"; ?> id="cat_<?php echo $cat->getId(); ?>" value="<?php echo $cat->getId(); ?>" aria-label="">
                                                    <label class="form-check-label"><?php echo $cat->getName(); ?></label>
                                                </div>

                                            <?php } ?>

                                        </div>
                                    </div>



                                    <!--div class="card shadow-sm mt-2 mb-2">
                                        <div class="card-body">
                                            <h5 class="card-title">Thumbnail Image</h5>
                                            <div class="form-group">
                                                <label>Upload Image</label>
                                                <div class="input-group container-fluid">
                                                    
                                                    <span class="input-group-btn">
                                                        <span class="btn btn-default btn-file">
                                                            <input type="file" id="imgInp">
                                                        </span>
                                                    </span>
                                                </div>
                                                <img width="200" height="100" id='img-upload' />
                                            </div>
                                        </div>
                                    </div-->

                                </div>
                            </div>
                        </div>


                    </div>


                </div>

                <!--page content-->
            </div>



        </div>
    </div>

    <div class="modal fade" id="modal_error" tabindex="-1" aria-labelledby="link" aria-hidden="true" disabled>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Error</h5>

                </div>
                <div class="modal-body">

                    <p id="error_msg" class="h6"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>

    <?php include_once "templates/footer.php"; ?>
    <script type="text/javascript">
        function enableImageResizeInDiv(id) {
            var editor = document.getElementById(id);
            var resizing = false;
            var currentImage;
            var createDOM = function(elementType, className, styles) {
                let ele = document.createElement(elementType);
                ele.className = className;
                setStyle(ele, styles);
                return ele;
            };
            var setStyle = function(ele, styles) {
                for (key in styles) {
                    ele.style[key] = styles[key];
                }
                return ele;
            };
            var removeResizeFrame = function() {
                document.querySelectorAll(".resize-frame,.resizer").forEach((item) => item.parentNode.removeChild(item));
            };
            var offset = function offset(el) {
                const rect = el.getBoundingClientRect(),
                    scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
                    scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                return {
                    top: rect.top + scrollTop,
                    left: rect.left + scrollLeft
                }
            };
            var clickImage = function(img) {
                removeResizeFrame();
                currentImage = img;
                const imgHeight = img.offsetHeight;
                const imgWidth = img.offsetWidth;
                const imgPosition = {
                    top: img.offsetTop,
                    left: img.offsetLeft
                };
                const editorScrollTop = editor.scrollTop;
                const editorScrollLeft = editor.scrollLeft;
                const top = imgPosition.top - editorScrollTop - 1;
                const left = imgPosition.left - editorScrollLeft - 1;

                editor.append(createDOM('span', 'resize-frame', {
                    margin: '10px',
                    position: 'absolute',
                    top: (top + imgHeight - 10) + 'px',
                    left: (left + imgWidth - 10) + 'px',
                    border: 'solid 3px blue',
                    width: '6px',
                    height: '6px',
                    cursor: 'se-resize',
                    zIndex: 1
                }));

                editor.append(createDOM('span', 'resizer top-border', {
                    position: 'absolute',
                    top: (top) + 'px',
                    left: (left) + 'px',
                    border: 'dashed 1px grey',
                    width: imgWidth + 'px',
                    height: '0px'
                }));

                editor.append(createDOM('span', 'resizer left-border', {
                    position: 'absolute',
                    top: (top) + 'px',
                    left: (left) + 'px',
                    border: 'dashed 1px grey',
                    width: '0px',
                    height: imgHeight + 'px'
                }));

                editor.append(createDOM('span', 'resizer right-border', {
                    position: 'absolute',
                    top: (top) + 'px',
                    left: (left + imgWidth) + 'px',
                    border: 'dashed 1px grey',
                    width: '0px',
                    height: imgHeight + 'px'
                }));

                editor.append(createDOM('span', 'resizer bottom-border', {
                    position: 'absolute',
                    top: (top + imgHeight) + 'px',
                    left: (left) + 'px',
                    border: 'dashed 1px grey',
                    width: imgWidth + 'px',
                    height: '0px'
                }));

                document.querySelector('.resize-frame').onmousedown = () => {
                    resizing = true;
                    return false;
                };

                editor.onmouseup = () => {
                    if (resizing) {
                        currentImage.style.width = document.querySelector('.top-border').offsetWidth + 'px';
                        currentImage.style.height = document.querySelector('.left-border').offsetHeight + 'px';
                        refresh();
                        currentImage.click();
                        resizing = false;
                    }
                };

                editor.onmousemove = (e) => {
                    if (currentImage && resizing) {
                        let height = e.pageY - offset(currentImage).top;
                        let width = e.pageX - offset(currentImage).left;
                        height = height < 1 ? 1 : height;
                        width = width < 1 ? 1 : width;
                        const top = imgPosition.top - editorScrollTop - 1;
                        const left = imgPosition.left - editorScrollLeft - 1;
                        setStyle(document.querySelector('.resize-frame'), {
                            top: (top + height - 10) + 'px',
                            left: (left + width - 10) + "px"
                        });

                        setStyle(document.querySelector('.top-border'), {
                            width: width + "px"
                        });
                        setStyle(document.querySelector('.left-border'), {
                            height: height + "px"
                        });
                        setStyle(document.querySelector('.right-border'), {
                            left: (left + width) + 'px',
                            height: height + "px"
                        });
                        setStyle(document.querySelector('.bottom-border'), {
                            top: (top + height) + 'px',
                            width: width + "px"
                        });
                    }
                    return false;
                };
            };
            var bindClickListener = function() {
                editor.querySelectorAll('img').forEach((img, i) => {
                    img.onclick = (e) => {
                        if (e.target === img) {
                            clickImage(img);
                        }
                    };
                });
            };
            var refresh = function() {
                bindClickListener();
                removeResizeFrame();
                if (!currentImage) {
                    return;
                }
                var img = currentImage;
                var imgHeight = img.offsetHeight;
                var imgWidth = img.offsetWidth;
                var imgPosition = {
                    top: img.offsetTop,
                    left: img.offsetLeft
                };
                var editorScrollTop = editor.scrollTop;
                var editorScrollLeft = editor.scrollLeft;
                const top = imgPosition.top - editorScrollTop - 1;
                const left = imgPosition.left - editorScrollLeft - 1;

                editor.append(createDOM('span', 'resize-frame', {
                    position: 'absolute',
                    top: (top + imgHeight) + 'px',
                    left: (left + imgWidth) + 'px',
                    border: 'solid 2px red',
                    width: '6px',
                    height: '6px',
                    cursor: 'se-resize',
                    zIndex: 1
                }));

                editor.append(createDOM('span', 'resizer', {
                    position: 'absolute',
                    top: (top) + 'px',
                    left: (left) + 'px',
                    border: 'dashed 1px grey',
                    width: imgWidth + 'px',
                    height: '0px'
                }));

                editor.append(createDOM('span', 'resizer', {
                    position: 'absolute',
                    top: (top) + 'px',
                    left: (left + imgWidth) + 'px',
                    border: 'dashed 1px grey',
                    width: '0px',
                    height: imgHeight + 'px'
                }));

                editor.append(createDOM('span', 'resizer', {
                    position: 'absolute',
                    top: (top + imgHeight) + 'px',
                    left: (left) + 'px',
                    border: 'dashed 1px grey',
                    width: imgWidth + 'px',
                    height: '0px'
                }));
            };
            var reset = function() {
                if (currentImage != null) {
                    currentImage = null;
                    resizing = false;
                    removeResizeFrame();
                }
                bindClickListener();
            };
            editor.addEventListener('scroll', function() {
                reset();
            }, false);
            editor.addEventListener('mouseup', function(e) {
                if (!resizing) {
                    const x = (e.x) ? e.x : e.clientX;
                    const y = (e.y) ? e.y : e.clientY;
                    let mouseUpElement = document.elementFromPoint(x, y);
                    if (mouseUpElement) {
                        let matchingElement = null;
                        if (mouseUpElement.tagName === 'IMG') {
                            matchingElement = mouseUpElement;
                        }
                        if (!matchingElement) {
                            reset();
                        } else {
                            clickImage(matchingElement);
                        }
                    }
                }
            });
        }
        enableImageResizeInDiv('editor');
    </script>



    <script type="text/javascript">
        var editor; //editor object
        var editorText; //editor text
        var currentSelectionRange; //current selection

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

        function setEditorMode(mode) {
            var textContent;
            if (mode) {
                textContent = document.createTextNode(editor.innerHTML);
                editor.innerHTML = "";
                var preformattedText = document.createElement("pre");
                editor.contentEditable = false;
                preformattedText.id = "sourceText";
                preformattedText.contentEditable = true;
                preformattedText.appendChild(textContent);
                editor.appendChild(preformattedText);
            } else {

                textContent = document.createRange();
                textContent.selectNodeContents(editor.firstChild);
                editor.innerHTML = textContent.toString();

                editor.contentEditable = true;
            }
            editor.focus();
        }

        function toggleModeClicked() {
            setEditorMode(document.getElementById("toggleModeButton").classList.contains("active"))
        }

        function insertImage(img) {
            editorCommand("insertimage", img);
            document.getElementById('inputImage').value = "";
        };

        function insertVideo(video_url) {
            var video_frame = '<iframe width="420" height="315" src="' + video_url + '"></iframe>'
            editorCommand("insertHTML", video_frame);
        }
    </script>



    <script type='text/javascript'>
        $(document).ready(function() {
            $("#sortable,#sortable1").sortable();
            $("#sortable,#sortable1").disableSelection();
        });
    </script>

    <script>
        /*
        $(document).ready(function() {
            $(document).on('change', '.btn-file :file', function() {
                var input = $(this),
                    label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [label]);
            });

            $('.btn-file :file').on('fileselect', function(event, label) {

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

                    reader.onload = function(e) {
                        $('#img-upload').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }

            $("#imgInp").change(function() {
                readURL(this);
            });
        });*/
    </script>

    <script>
        function encodeSlug($str) {
            const regex = /\[|\]|\(|\)|\{|\}|\.|:|,|;|\!|\"|\£|\$|\%|\&|\=|\?|\^|\_|\\|\/|\s/gi;
            let out = "";

            out = $str.replaceAll(regex, '-');
            out = out.toLowerCase();
            return out;
        }

        class Post {
            constructor(title, content, cat_id, meta_title, meta_description) {
                this.title = title;
                this.content = content;
                this.cat_id = cat_id;
                this.meta_title = meta_title;
                this.meta_description = meta_description;
            }
        }



        const title_input = document.getElementById('post_name_input');
        const slug_input = document.getElementById('SlugInput');

        title_input.addEventListener('change', (event) => {
            if (document.getElementById("SlugInput").value == "")
                document.getElementById("SlugInput").value = encodeSlug(document.getElementById('post_name_input').value);
        });

        slug_input.addEventListener('change', (event) => {
            slug_input.value = encodeSlug(SlugInput.value);
        });

        function createPost() {
            if (document.getElementById('post_name_input').value == "") {
                document.getElementById('error_msg').innerHTML = "Please enter a valid Post Title.";
                $('#modal_error').modal('show');
            } else {

                var cat_radios = document.getElementsByName('category');
                var cat_id;
                for (var i = 0, length = cat_radios.length; i < length; i++) {
                    if (cat_radios[i].checked) {
                        cat_id = cat_radios[i].value;
                        break;
                    }
                }

                var content = "";

                if (document.getElementById('sourceText')) {
                    content = document.getElementById('sourceText').innerHTML;
                } else {
                    content = document.getElementById('editor').innerHTML;
                }

                var post = new Post(
                    document.getElementById('post_name_input').value,
                    content,
                    cat_id,
                    document.getElementById('metaTitleInput').value,
                    document.getElementById('metaDescriptionInput').value
                )

                $.redirect('', post);
            }

        }
    </script>

    <script>
        //tooltips
        $(document).ready(function() {
            $("body").tooltip({
                selector: '[data-toggle=tooltip]'
            });
        });
    </script>
</body>

</html>