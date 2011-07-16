<script src="/js/dynatree/jquery/jquery.js" type="text/javascript"></script>
<script src="/js/dynatree/jquery/jquery-ui.custom.js" type="text/javascript"></script>
<script src="/js/dynatree/jquery/jquery.cookie.js" type="text/javascript"></script>

<link href="/js/dynatree/src/skin-vista/ui.dynatree.css" rel="stylesheet" type="text/css">
<script src="/js/dynatree/src/jquery.dynatree.js" type="text/javascript"></script>

<!-- (Irrelevant source removed.) -->

<script type="text/javascript">
    $(function(){
        $("#tree").dynatree({
            onDblClick: function(node) {
                if( node.data.url )
                    window.open(node.data.url, node.data.target);
            },
            debugLevel: 0,
            minExpandLevel: 1,
            // In real life we would call a URL on the server like this:
            initAjax: {
                url: "{$PageAddress}",
                type:"post",
                data: { handlerBindSectionTree: "handlerBindSectionTree",
                    moduleId: " {$Module->moduleId}"
                }
            }
            ,
            dnd: {
                onDragStart: function(node) {
                    /** This function MUST be defined to enable dragging for the tree.
                     *  Return false to cancel dragging of node.
                     */
                    //logMsg("tree.onDragStart(%o)", node);
                    return true;
                },
                onDragStop: function(node) {
                    // This function is optional.
                    // logMsg("tree.onDragStop(%o)", node);
                },
                autoExpandMS: 1000,
                preventVoidMoves: true, // Prevent dropping nodes 'before self', etc.
                onDragEnter: function(node, sourceNode) {
                    /** sourceNode may be null for non-dynatree droppables.
                     *  Return false to disallow dropping on node. In this case
                     *  onDragOver and onDragLeave are not called.
                     *  Return 'over', 'before, or 'after' to force a hitMode.
                     *  Return ['before', 'after'] to restrict available hitModes.
                     *  Any other return value will calc the hitMode from the cursor position.
                     */
                    // logMsg("tree.onDragEnter(%o, %o)", node, sourceNode);
                    // Prevent dropping a parent below it's own child
                    //                if(node.isDescendantOf(sourceNode))
                    //                    return false;
                    // Prevent dropping a parent below another parent (only sort
                    // nodes under the same parent)
                    //                if(node.parent !== sourceNode.parent)
                    //                    return false;
                    //              if(node === sourceNode)
                    //                  return false;
                    // Don't allow dropping *over* a node (would create a child)
                    //        return ["before", "after"];
                    return true;
                },
                onDragOver: function(node, sourceNode, hitMode) {
                    /** Return false to disallow dropping this node.
                     *
                     */
                    // logMsg("tree.onDragOver(%o, %o, %o)", node, sourceNode, hitMode);
                    // Prohibit creating childs in non-folders (only sorting allowed)
                    //        if( !node.isFolder && hitMode == "over" )
                    //          return "after";
                },
                onDrop: function(node, sourceNode, hitMode, ui, draggable) {
                    /** This function MUST be defined to enable dropping of items on
                     * the tree.
                     */
                    //logMsg("tree.onDrop(%o, %o, %s)", node, sourceNode, hitMode);
                    if(hitMode=="before"){
                        $.ajax({
                            type: "POST",
                            url: "{$PageAddress}",
                            data: "handlerBtnBefore=handlerBtnBefore&moduleId={$Module->moduleId}&firstid="+node.data.id+"&secondid="+sourceNode.data.id,
                            success: function(msg){
                                //alert( "Data Saved: " + msg );
                            }
                        });
                    }
                    if(hitMode=="after"){
                        $.ajax({
                            type: "POST",
                            url: "{$PageAddress}",
                            data: "handlerBtnAfter=handlerBtnAfter&moduleId={$Module->moduleId}&firstid="+node.data.id+"&secondid="+sourceNode.data.id,
                            success: function(msg){
                                //alert( "Data Saved: " + msg );
                            }
                        });
                    }
                    if(hitMode=="over"){
                        $.ajax({
                            type: "POST",
                            url: "{$PageAddress}",
                            data: "handlerBtnOver=handlerBtnOver&moduleId={$Module->moduleId}&firstid="+node.data.id+"&secondid="+sourceNode.data.id,
                            success: function(msg){
                                //alert( "Data Saved: " + msg );
                            }
                        });
                    }
                    sourceNode.move(node, hitMode);
                    // expand the drop target
                    //        sourceNode.expand(true);
                },
                onDragLeave: function(node, sourceNode) {
                    /** Always called if onDragEnter was called.
                     */
                    //logMsg("tree.onDragLeave(%o, %o)", node, sourceNode);
                }
            }
        });

        $("#btnAdd").click(function(){
            var node = $("#tree").dynatree("getActiveNode");
            if(node){
                $.ajax({
                    type: "POST",
                    url: "{$PageAddress}",
                    data: "handlerBtnNew=handlerBtnNew&moduleId={$Module->moduleId}&parentId="+node.data.id+"&title=Новый раздел",
                    success: function(url){
                        var childNode = node.addChild({
                            title: "Новый раздел",
                            tooltip: "Новый раздел",
                            url:url,
                            "target":"_self"
                        });
                        node.expand(true);
                    }
                });
            }else{
                $.ajax({
                    type: "POST",
                    url: "{$PageAddress}",
                    data: "handlerBtnNew=handlerBtnNew&moduleId={$Module->moduleId}&parentId=-1&title=Новый раздел",
                    success: function(url){
                        var rootNode = $("#tree").dynatree("getRoot");
                        var childNode = rootNode.addChild({
                            title: "Новый раздел",
                            tooltip: "Новый раздел",
                            url:url,
                            "target":"_self"
                        });
                    }
                });
            }
        });

        $("#btnDelete").click(function(){
            var node = $("#tree").dynatree("getActiveNode");


            if (confirm('Удалить раздел? Все вложенные разделы и товары будут удалены безвозратно.'))
            {
                if(node){
                    node.remove();
                    $.ajax({
                        type: "POST",
                        url: "{$PageAddress}",
                        data: "handlerBtnDelete=handlerBtnDelete&moduleId={$Module->moduleId}&sectionId="+node.data.id,
                        success: function(msg){
                            //alert( "Data Saved: " + msg );
                        }
                    });
                }

            }
        });

    });


</script>
<div id="tree">  </div>
<div class="buttons">
    <input type="button" id="btnAdd" class="button" value="Добавить раздел" />
    <input type="button" id="btnDelete" class="button" value="Удалить раздел" />
</div>