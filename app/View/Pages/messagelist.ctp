<div class="container">
    <nav class="nav mt-3">
        <li class="nav-item">
            <a class="nav-link disabled" tabindex="-1" href="<?= Router::url('/') ?>">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link disabled text-capitalize" href="<?= Router::url('/edit') ?>" tabindex="-1" aria-disabled="true">edit profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="<?= Router::url('/messages') ?>" aria-disabled="true">Messages</a>
        </li>
        <li class="nav-item">
            <a href="<?= Router::url('/logout') ?>" class="nav-link text-danger">Logout</a>
        </li>
    </nav>
    <div>
        <div class="mb-5">
            <h1>Message Detail</h1>
        </div>
        <ul class="row d-flex flex-column" id="messagelist">

        </ul>
    </div>
</div>


<?= $this->Html->script('jquery') ?>
<?= $this->Html->script('sweetalert') ?>
<?= $this->Html->script('bootstrap') ?>

<script>
    $(document).ready(function() {
        const id = '<?= $id ?>';
        let listoffset = 1,
            listcounter = 0,
            deleted = 0;
        const datearrange = (datetime) => {
            let [d, t] = datetime.split(' ');
            let D = d.split('-').join('/');
            let T = t.split(':').splice(0, 2).join(':');

            return `${D} ${T}`;

        }

        function getchats(lo, lc, dltd) {
            let chats = "";
            $.getJSON(`<?= Router::url('/getchats') ?>/<?= $convowith ?>?&offset=${lo}&counter=${lc}&deleted=${dltd}`, function(data) {
                console.log(data);
                listcounter = data[0];
                if (data[1].length === 0) {
                    chats =
                        `
						<li style="list-style: none; border: solid gray 1px; border-radius: 25px; gap: 0 30px;" class="p-2 d-flex mb-3">
							<div class="h5 text-secondary">
								No Message!
							</div>
						</li>
					`;
                } else {
                    data[1].forEach((ele) => {
                        let convoid, imgname, content, date, name;
                        // filter out
                        content = ele.m.content;
                        date = ele.m.date;
                        imgname = ele.us.sender_img === null ? '../img/default.jpg' : `../img/${ele.us.sender_img}`;
                        convoid = ele.m.id;
                        if (ele.m.sender !== id) {
                            name = ele.ur.receiver_name;
                            chats +=
                                `
							<li id="convo_${convoid}" style="list-style: none; border: solid gray 1px; border-radius: 25px; gap: 0 30px;" class="p-2 d-flex mb-3 justify-content-end">
                                <div>
                                    <div class="text-primary h5 goto_${convoid}">${name}</div>
									<div class="mb-2 goto_${convoid}">${content}</div>
									<div class="d-flex justify-content-end align-items-center" style="gap: 0 800px;">
                                        <div class="text-danger" id="delete_${convoid}">Delete</div>
										<div class="text-secondary goto_${convoid}" style="font-size: 10px;">${datearrange(date)}</div>
									</div>
								</div>
								<div class="goto_${convoid}">
									<img src="${imgname}" height="100" width="100" style="border-radius: 50%;">
								</div>
							</li>
						`;
                        } else {
                            name = "You";
                            chats +=
                                `
							<li id="convo_${convoid}" style="list-style: none; border: solid gray 1px; border-radius: 25px; gap: 0 30px;" class="p-2 d-flex mb-3">
								<div class="goto_${convoid}">
									<img src="${imgname}" height="100" width="100" style="border-radius: 50%;">
								</div>
								<div>
                                    <div class="text-primary h5 goto_${convoid}">${name}</div>
									<div class="mb-2 goto_${convoid}">${content}</div>
									<div class="d-flex justify-content-start align-items-center" style="gap: 0 800px;">
										<div class="text-secondary goto_${convoid}" style="font-size: 10px;">${datearrange(date)}</div>
										<div class="text-danger text-end" id="delete_${convoid}">Delete</div>
									</div>
								</div>
							</li>
						`;
                        }
                    });
                    if (listoffset < listcounter) {
                        chats += `<p class="text-center" id="showlist">Show More</p>`;
                    }
                }
                $("#messagelist").append(chats);
                $("p[id='showlist']").click(function() {
                    $(this).remove();
                    listoffset++;
                    getchats(listoffset, listcounter, deleted);
                });

                $("div[id^='delete_']").on('click mouseover mouseleave', function(e) {
                    const thisid = $(this).attr("id").split("_")[1];
                    if (e.type === 'click') {
                        $(`#convo_${thisid}`).addClass('customdelete');
                        $.ajax({
                            url: '<?= Router::url('/deletemessage') ?>',
                            method: 'POST',
                            data: {
                                id: thisid
                            },
                            success: function(data) {
                                if (data === 'success') {
                                    setTimeout(() => {
                                        $(`#convo_${thisid}`).remove();
                                    }, 1500);
                                    deleted++;
                                } else {
                                    $(`#convo_${thisid}`).removeClass('customdelete');
                                }
                            }
                        });
                    } else if (e.type === 'mouseover') {
                        $(this).addClass('bg-dark');
                    } else if (e.type === 'mouseleave') {
                        $(this).removeClass('bg-dark');
                    }


                });
            });
        }

        getchats(listoffset, listcounter, deleted);
    });
</script>