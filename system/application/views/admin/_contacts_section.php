<?php
    $contact_block_index = 0;
    if (! empty($contacts->contact_phones)) {
        $phones = json_decode($contacts->contact_phones, true);
        $index  = 0;
        foreach ($phones as $index => $phone) {
            ?>
            <div id="contact_block_<?= $contact_block_index ?>">
                <select id="contact_type_<?= $contact_block_index ?>">
                    <option value="phone" selected>телефон</option>
                    <option value="fax">факс</option>
                    <option value="email">e-mail</option>
                </select>
                <input type="text" id="contact_value_<?= $contact_block_index ?>" value="<?= $phone ?>"/>
            </div>
            <?php
            $contact_block_index ++;
        }
    }
    if (! empty($contacts->contact_faxes)) {
        $faxes = json_decode($contacts->contact_faxes, true);
        $index = 0;
        foreach ($faxes as $index => $fax) {
            ?>
            <div id="contact_block_<?= $contact_block_index ?>">
                <select id="contact_type_<?= $contact_block_index ?>">
                    <option value="phone">телефон</option>
                    <option value="fax" selected>факс</option>
                    <option value="email">e-mail</option>
                </select>
                <input type="text" id="contact_value_<?= $contact_block_index ?>" value="<?= $fax ?>"/>
            </div>
            <?php
            $contact_block_index ++;
        }
    }
    if (! empty($contacts->contact_emails)) {
        $emails = json_decode($contacts->contact_emails, true);
        $index  = 0;
        foreach ($emails as $index => $email) {
            ?>
            <div id="contact_block_<?= $contact_block_index ?>">
                <select id="contact_type_<?= $contact_block_index ?>">
                    <option value="phone">телефон</option>
                    <option value="fax">факс</option>
                    <option value="email" selected>e-mail</option>
                </select>
                <input type="text" id="contact_value_<?= $contact_block_index ?>" value="<?= $email ?>"/>
            </div>
            <?php
            $contact_block_index ++;
        }
    }
?>
