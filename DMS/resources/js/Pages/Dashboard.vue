<!-- LAST UPDATED BY: GIAN -->
<template>
    <!--header-->
    <nav class="navbar navbar-expand-md sticky-top header-main">
        <div class="container-fluid align-items-center">
            <div class="navbar-brand d-flex gap-2 ms-3" href=""> 
                <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                <div class="d-flex flex-column row-gap-0">
                    <div class="header-uname">Name</div>
                    <div class="header-role">Admin</div>
                </div>
            </div>
            <div class="d-flex me-4 gap-4"><!--right side-->
                <div>
                    <a class="header-nav" href="">Schedule</a>
                </div>
                <div>
                    <a class="header-nav" href="">Assignment</a>
                </div>
                <div>
                    <a class="header-nav" href="">Support</a>
                </div>
            </div>
        </div>
    </nav>
    <!--body-->
    <div class="container-fluid page-content px-4">
        <div class="row gap-3 row-gap-1">
            <div class="d-flex flex-column col-xl-7 col-lg page-group p-4"><!--management-->
                <div class="d-flex">
                    <div class="page-group-title">Management</div>
                    <div class="ms-auto"></div><!--tag stuff supposed to be here-->
                    <div>
                        <input type="text" class="page-search" placeholder="Search">
                    </div>
                </div>
                <div class="d-flex">
                    <ul class="nav nav-underline justify-content-start">
                        <li class="nav-item">
                            <button class="page-man-tab nav-link active" id="man-employee-tab" data-bs-toggle="tab" data-bs-target="#employee-tab-pane" type="button" role="tab" aria-controls="employee-tab-pane" aria-selected="true">Employee</button>
                        </li>
                        <li class="nav-item">
                            <button class="page-man-tab nav-link" id="man-hospital-tab" data-bs-toggle="tab" data-bs-target="#hospital-tab-pane" type="button" role="tab" aria-controls="hospital-tab-pane" aria-selected="false">Hospitals</button>
                        </li>
                        <li class="nav-item">
                            <button class="page-man-tab nav-link " id="man-user-tab" data-bs-toggle="tab" data-bs-target="#user-tab-pane" type="button" role="tab" aria-controls="user-tab-pane" aria-selected="false">Users</button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2">
                    <div class="tab-pane show active" id="employee-tab-pane" role="tabpanel" aria-labelledby="man-employee-tab" tabindex="0">
                        <div class="d-flex justify-content-evenly">
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">Employee 1</div>
                                </div>
                            </div>
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">Employee 2</div>
                                </div>
                            </div>
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">Employee 3</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="hospital-tab-pane" role="tabpanel" aria-labelledby="man-hospital-tab" tabindex="0">
                        <div class="d-flex justify-content-evenly">
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">Hospital 1</div>
                                </div>
                            </div>
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">Hospital 2</div>
                                </div>
                            </div>
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">Hospital 3</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="user-tab-pane" role="tabpanel" aria-labelledby="man-user-tab" tabindex="0">
                        <div class="d-flex justify-content-evenly">
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">User 1</div>
                                </div>
                            </div>
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">User 2</div>
                                </div>
                            </div>
                            <div class="man-item">
                                <img src="https://dummyimage.com/160x160/d1d1d1/fff.png" />
                                <div class="pt-2">
                                    <div class="item-subtext">Heading</div>
                                    <div class="item-text">User 3</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex mt-3 justify-content-between">
                    <nav aria-label="...">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="pagi-override page-item active">
                                <a class="page-link pagi-override" aria-current="page">1</a>
                            </li>
                            <li class="pagi-override page-item"><a class="pagi-override page-link" href="#">2</a></li>
                            <li class="pagi-override page-item"><a class="pagi-override page-link" href="#">3</a></li>
                        </ul>
                    </nav>
                    <button class="btn btn-sm px-5 home-btn">Create New</button>
                </div>
            </div>
            <div class="d-flex col-xl page-group p-4"><!--dashboard-->
                <div class="page-group-title">Dashboard</div>
            </div>
            <div class="w-100"></div>
            <div class="d-flex col-xl-2 col-lg page-group p-4"><!--app usage-->
                <div class="d-flex flex-column w-100">
                    <div class="item-subtext">System Monitoring</div>
                    <div class="item-text">Application Usage</div>
                    <hr class="my-2">
                    <div class="d-flex flex-column row-gap-1">
                        <div class="d-flex">
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                            <div class="d-flex flex-column ms-3 flex-grow-1">
                                <div class="item-text-light">Menu Label</div>
                                <div class="item-subtext">Menu description.</div>
                            </div>
                            <div class="item-text-light">⇧A</div>
                        </div>
                        <div class="d-flex">
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                            <div class="d-flex flex-column ms-3 flex-grow-1">
                                <div class="item-text-light">Menu Label</div>
                                <div class="item-subtext">Menu description.</div>
                            </div>
                            <div class="item-text-light">⇧A</div>
                        </div>
                        <div class="d-flex">
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                            <div class="d-flex flex-column ms-3 flex-grow-1">
                                <div class="item-text-light">Menu Label</div>
                                <div class="item-subtext">Menu description.</div>
                            </div>
                            <div class="item-text-light">⇧A</div>
                        </div>
                        <hr class="m-2">
                        <div class="d-flex">
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                            <div class="d-flex flex-column ms-3 flex-grow-1">
                                <div class="item-text-light">Menu Label</div>
                                <div class="item-subtext">Menu description.</div>
                            </div>
                            <div class="item-text-light">⇧A</div>
                        </div>
                    </div>
                    <button class="btn btn-sm px-5 mt-3 home-btn">View More</button>
                </div>
            </div>
            <div class="d-flex flex-column col-xl page-group p-4"><!--documents-->
                <div class="d-flex">
                    <div class="page-group-title">Documents</div>
                    <div class="ms-auto"></div><!--tag stuff supposed to be here-->
                    <div>
                        <input type="text" class="page-search" placeholder="Search">
                    </div>
                </div>
                <div class="d-flex mt-2 gap-3 flex-wrap justify-content-evenly">
                    <div class="doc-item">
                        <div class="d-flex justify-content-between">
                            <div class="doc-name">Document.doc</div>
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2"> 
                            <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                            <div class="d-flex flex-column row-gap-0">
                                <div class="header-uname">Title</div>
                                <div class="header-role">Description</div>
                            </div>
                        </div>
                    </div>
                    <div class="doc-item">
                        <div class="d-flex justify-content-between">
                            <div class="doc-name">Document.doc</div>
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2"> 
                            <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                            <div class="d-flex flex-column row-gap-0">
                                <div class="header-uname">Title</div>
                                <div class="header-role">Description</div>
                            </div>
                        </div>
                    </div>
                    <div class="doc-item">
                        <div class="d-flex justify-content-between">
                            <div class="doc-name">Document.doc</div>
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2"> 
                            <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                            <div class="d-flex flex-column row-gap-0">
                                <div class="header-uname">Title</div>
                                <div class="header-role">Description</div>
                            </div>
                        </div>
                    </div>
                    <div class="doc-item">
                        <div class="d-flex justify-content-between">
                            <div class="doc-name">Document.doc</div>
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2"> 
                            <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                            <div class="d-flex flex-column row-gap-0">
                                <div class="header-uname">Title</div>
                                <div class="header-role">Description</div>
                            </div>
                        </div>
                    </div>
                    <div class="doc-item">
                        <div class="d-flex justify-content-between">
                            <div class="doc-name">Document.doc</div>
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2"> 
                            <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                            <div class="d-flex flex-column row-gap-0">
                                <div class="header-uname">Title</div>
                                <div class="header-role">Description</div>
                            </div>
                        </div>
                    </div>
                    <div class="doc-item">
                        <div class="d-flex justify-content-between">
                            <div class="doc-name">Document.doc</div>
                            <div>
                                <span class="material-symbols-outlined">star</span>
                            </div>
                        </div>
                        <div class="d-flex gap-2 mt-2"> 
                            <img src="" alt="" width="35" height="35" class="d-inline-block align-text-top"/>
                            <div class="d-flex flex-column row-gap-0">
                                <div class="header-uname">Title</div>
                                <div class="header-role">Description</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex mt-3 justify-content-between">
                    <nav aria-label="...">
                        <ul class="pagination pagination-sm mb-0">
                            <li class="pagi-override page-item active">
                                <a class="page-link pagi-override" aria-current="page">1</a>
                            </li>
                            <li class="pagi-override page-item"><a class="pagi-override page-link" href="#">2</a></li>
                            <li class="pagi-override page-item"><a class="pagi-override page-link" href="#">3</a></li>
                        </ul>
                    </nav>
                    <button class="btn btn-sm px-5 home-btn">Create Document</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
</script>

<style scoped>
body{
    background-color: #fff;
    font-family: "Poppins", sans-serif !important;
}
a{
    text-decoration: none !important;
    color: inherit;
}
input{
    border: solid 1px rgba(102,102,102,.35);
    border-radius: 12px;
    font-family: "Poppins", sans-serif;
    font-weight: 400;
    font-style: normal;
    color: #333;
    outline: none;
}
.header-main{
    height: 9vh;
    background-color: #fff;
    border-bottom: 1px solid #d9d9d9;
    width: 100%;
    top: 0;
}
.header-uname{
    font-weight: 600;
    font-style: normal;
    font-size: 15px;
    color: #757575;
}
.header-role{
    font-weight: 500;
    font-style: normal;
    font-size: 14px;
    color: #b3b3b3;
}
.header-nav{
    font-weight: 500;
    font-style: normal;
    font-size: 15px;
    color: #1e1e1e;
}

/* main body */
.page-content{
    background-color: #fbfbfb;
    padding: 10px 20px;
    height: 91vh;
}
.page-group{
    background-color: #fff;
    border: solid 1px #d9d9d9;
}
.page-group-title{
    font-weight: 600;
    font-style: normal;
    font-size: 24px;
    color: #1e1e1e;
}
.page-search{
    font-weight: 500;
    font-size: 12px;
    color: #1e1e1e;
    padding: 5px 10px;
}
.page-man-tab{
    font-weight: 400;
    color: #303030;
}
.page-man-tab:hover{
    font-weight: 400;
    color: #303030;
    border-bottom-color: #303030;
}
.man-item{
    width: 241px;
    border: solid 1px #d9d9d9;
    border-radius: 8px;
    padding: 24px;
}
.man-item img{
    margin: auto;
}
.item-subtext{
    font-weight: 400;
    font-size: 14px;
    color: #757575;
}
.item-text{
    font-weight: 600;
    font-size: 16px;
    color: #1e1e1e;
}
.item-text-light{
    font-weight: 400;
    font-size: 16px;
    color: #1e1e1e;
}
.pagi-override{
    color: #757575;
    background-color: #f5f5f5;
}
.pagi-override:hover{
    color: #1e1e1e;
}
.active > .pagi-override{
    background-color: #757575;
    border-color: #757575;
}
.home-btn{
    background-color: #2c2c2c;
    color: #f5f5f5;
}
.home-btn:hover{
    background-color: #f5f5f5;
    border-color: #2c2c2c;
    color: #2c2c2c;
}
.doc-item{
    width: 306px;
    height: 132px;
    border: solid 1px #d9d9d9;
    border-radius: 8px;
    padding: 20px;
}
.doc-name{
    font-size: 22px;
    font-weight: 600;
    color: #1e1e1e;
}
</style>
