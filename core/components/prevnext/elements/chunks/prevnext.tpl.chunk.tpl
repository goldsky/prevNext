
                            <div class="row">
                                <div class="col-sm-6">
                                    [[+prev.id:notempty=`
                                    <h5>Previous Post</h5>
                                    <div class="well well-sm">
                                        <a href="[[~[[+prev.id]]? &scheme=`full`]]">&laquo; [[+prev.pagetitle]]</a>
                                    </div>
                                    `]]
                                </div>
                                <div class="col-sm-6 text-right">
                                    [[+next.id:notempty=`
                                    <h5>Next Post</h5>
                                    <div class="well well-sm">
                                        <a href="[[~[[+next.id]]? &scheme=`full`]]">[[+next.pagetitle]] &raquo;</a>
                                    </div>
                                    `]]
                                </div>
                            </div>
