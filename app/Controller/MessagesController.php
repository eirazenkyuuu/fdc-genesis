<?php

if (!function_exists('dd')) {
    function dd($data)
    {
        var_dump($data);
        die();
    }
}
class MessagesController extends AppController
{
    public $uses = array('Message');

    public function sendmessage()
    {
        if ($this->request->is('post')) {
            $this->Message->set($this->request->data);
            if ($this->Message->validates()) {
                if ($this->Message->create($this->request->data)) {
                    $savedData = $this->Message->save();
                    echo json_encode($savedData);
                } else {
                    echo json_encode(["error"]);
                }
            }
        }
        exit();
    }

    public function convolist()
    {

        $id = $this->request->query('id');
        $deleted = (int)$this->request->query('deleted');
        $offset = (((int)$this->request->query('offset') - 1) * 10) - $deleted;
        $count = (int)$this->request->query('count');

        // for counting and pagination purposes
        $countquery = "SELECT 
                    m.sender, 
                    m.receiver, 
                    ur.name AS receiver_name, 
                    ur.img_name AS receiver_img,
                    us.name AS sender_name, 
                    us.img_name AS sender_img,
                    m.content, 
                    m.date
                FROM 
                    messages AS m
                INNER JOIN 
                    users AS ur ON ur.id = m.receiver
                INNER JOIN 
                    users AS us ON us.id = m.sender
                WHERE 
                    (m.receiver = :id OR m.sender = :id)
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM messages AS m2 
                        WHERE 
                            (m2.sender = m.receiver AND m2.receiver = m.sender) 
                            AND m2.id > m.id
                    )
                ORDER BY 
                    m.date DESC";
        $result = $this->Message->query($countquery, ['id' => $id]);
        $count = floor(sizeof($result) / 10) + (sizeof($result) % 10 === 0 ? 0 : 1);

        // Algorithm

        // kuhaon tanan row sa message
        // i join ang mga name sa receiver ug sender
        // use alias para dili maglibog ang users nga table
        // pag maquery na na diha
        // get only one on the row kung nag equals ang sender(id) ug receiver(id) reciprocally para ma filter out ang convolist
        // kuhaon lang ang kadtong latest date pero pwede ra ang id since ang id is incremented
        $query =
            "SELECT 
                    m.sender, 
                    m.receiver, 
                    ur.name AS receiver_name, 
                    ur.img_name AS receiver_img,
                    us.name AS sender_name, 
                    us.img_name AS sender_img,
                    m.content, 
                    m.date
                FROM 
                    messages AS m
                INNER JOIN 
                    users AS ur ON ur.id = m.receiver
                INNER JOIN 
                    users AS us ON us.id = m.sender
                WHERE 
                    (m.receiver = :id OR m.sender = :id)
                    AND NOT EXISTS (
                        SELECT 1 
                        FROM messages AS m2 
                        WHERE 
                            (m2.sender = m.receiver AND m2.receiver = m.sender) 
                            AND m2.id > m.id
                    )
                ORDER BY 
                    m.date DESC limit 10 offset $offset";

        $data = $this->Message->query($query, ['id' => $id]);
        echo json_encode([$count, $data]);
        exit();
    }

    public function deleteconvo()
    {
        if ($this->request->is('post')) {
            $id = $this->Auth->user()['User']['id'];
            $convowith = $this->request->data['convowith'];

            $success = false;

            // Delete messages where sender = $id and receiver = $convowith
            $result1 = $this->Message->query("DELETE FROM messages WHERE sender = ? AND receiver = ?", [$id, $convowith]);
            if ($result1) {
                $success = true;
            }

            // Delete messages where receiver = $id and sender = $convowith
            $result2 = $this->Message->query("DELETE FROM messages WHERE receiver = ? AND sender = ?", [$id, $convowith]);
            if ($result2) {
                $success = true;
            }

            if ($success) {
                echo "success";
            } else {
                echo "error";
            }

            exit();
        }
    }

    public function getchats($convowith = null)
    {
        $id = $this->Auth->user()['User']['id'];
        $deleted = (int)$this->request->query('deleted');
        $offset = (((int)$this->request->query('offset') - 1) * 10) - $deleted;
        $count = (int)$this->request->query('count');

        // for counting and pagination purposes
        $countquery = "SELECT count(id) from messages where (receiver = :id and sender = :convowith) or (receiver = :convowith and sender = :id)";
        $result = $this->Message->query($countquery, ['id' => $id, 'convowith' => $convowith]);
        $count = floor(sizeof($result) / 10) + (sizeof($result) % 10 === 0 ? 0 : 1);

        $sql = "SELECT
                m.id,
                m.sender,
                m.receiver,
                ur.name AS receiver_name,
                ur.img_name AS receiver_img,
                us.name AS sender_name,
                us.img_name AS sender_img,
                m.content,
                m.date
            FROM 
                messages as m 
            INNER JOIN 
                users AS ur ON ur.id = m.receiver 
            INNER JOIN 
                users AS us ON us.id = m.sender 
            WHERE 
                (m.receiver = '$id' AND m.sender = '$convowith')
                OR
                (m.receiver = '$convowith' AND m.sender = '$id')
            ORDER BY 
                m.date DESC limit 10 offset $offset;";

        $data = $this->Message->query($sql);

        echo json_encode([$count, $data]);
        exit();
    }

    public function deletemessage()
    {
        if ($this->request->is('post')) {
            $id = $this->request->data['id'];

            $query = $this->Message->query("DELETE from messages where id = ?", [$id]);

            if ($query) {
                echo "success";
            } else {
                echo "error";
            }
        }
        exit();
    }
}
