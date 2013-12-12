<style type="text/css">
h2 {
    display: block;
    font-size: 1.5em;
    font-weight: bold;
    margin: 0.83em 0;
}
#toolsPage h2 {
    color: #464646;
}
#toolsPage h2 {
    font: italic 24px/35px Georgia,"Times New Roman","Bitstream Charter",Times,serif;
    margin: 0;
    padding: 14px 15px 3px 0;
    text-shadow: 0 1px 0 #FFFFFF;
}
#toolsPage a{
    color: #21759B;
    font-size: 14px;
    font-weight: bold;
}
</style>
<div id="toolsPage">
    <div class="section_block"><h2>Установки опций для SEO</h2></div>
    <div style="float: left;">
        <form method="post" action="" name="dofollow">
            <table>
                <tbody>
                <tr>
                    <th style="text-align:right; vertical-align:top;" scope="row">
                        <a href="#" title="Help for Option Home Title" target="_blank">Title-тэг главной страницы:&nbsp;</a>
                    </th>
                    <td>
                        <label>
                            <textarea name="aiosp_home_title" rows="2" cols="60"></textarea>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:right; vertical-align:top;" scope="row">
                        <a href="#" title="Help for Option Home Description" target="_blank">Description-тэг главной страницы:&nbsp;</a>
                    </th>
                    <td>
                        <label>
                            <textarea name="aiosp_home_description" rows="2" cols="60"></textarea>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:right; vertical-align:top;" scope="row">
                        <a href="#" title="Help for Option Home Keywords" target="_blank">Keywords-тэг главной страницы (разделяйте запятой):&nbsp;</a>
                    </th>
                    <td>
                        <label>
                            <textarea name="aiosp_home_keywords" rows="2" cols="60"></textarea>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:right; vertical-align:top;" scope="row">
                        <a href="#" title="Help for Option Rewrite Titles" target="_blank">Перезаписывать описание Titles-тэгов:&nbsp;</a>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" checked="1" name="aiosp_rewrite_titles">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:right; vertical-align:top;" scope="row">
                        <a title="Help for Autogenerate Descriptions" target="_blank">Автогенерировать описание Descriptions-тэгов:&nbsp;</a>
                    </th>
                    <td>
                        <label>
                            <input type="checkbox" checked="1" name="aiosp_generate_descriptions">
                        </label>
                    </td>
                </tr>
                <tr>
                    <th style="text-align:right; vertical-align:top;" scope="row">
                        <a title="Help for Option Max Number of Words in Auto-Generated Descriptions" target="_blank">
                            Максимальное число слов в автосгенерированном описании (Descriptions):&nbsp;</a>
                    </th>
                    <td>
                        <label>
                            <input value="25" name="aiosp_max_words_excerpt" size="5">
                        </label>
                    </td>
                </tr>
                </tbody>
            </table>
            <p class="submit">
                <div style="margin-top: 35px; width: 200px;" onclick="javascript:save_item('2', 'about');return false;" class="apply_all_btn">
                    <span>Сохранить</span>
                </div>
            </p>
        </form>
    </div>
</div>