<?php

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product, $ciyashop_options;

$productName = 'Репка Двушка';
$description = 'Это компактная теплица.  Базовая версия включает в себя 2 двери. Состоит из каркаса и обшивки из сотового поликарбоната. Каркас выполнен из профильной оцинкованной трубы 20х20 мм, что обеспечивает умеренную способность выдерживать сезонные нагрузки. Каркас из одной дуги отлично подойдет для использования теплицы в теплых регионах и областях, где низкий или средний уровень снеговой нагрузкой зимой.';
$imagePath = 'https://xn--m1ao.xn--p1ai/upload/iblock/99a/99ac0779a689e10aee38f427b3b299ab.png';

$attributes = $product->get_variation_attributes();
$default_attributes = $product->get_default_attributes();

$dimensionsAttributes = [];
if (isset($attributes['pa_width']))
    $dimensionsAttributes['pa_width'] = $attributes['pa_width'];
if (isset($attributes['pa_length']))
    $dimensionsAttributes['pa_length'] = $attributes['pa_length'];

$baseOptionsAttributes = [];
if (isset($attributes['pa_construction_type']))
    $baseOptionsAttributes['pa_construction_type'] = $attributes['pa_construction_type'];
if (isset($attributes['pa_frame_protection']))
    $baseOptionsAttributes['pa_frame_protection'] = $attributes['pa_frame_protection'];
if (isset($attributes['pa_polycarbonate']))
    $baseOptionsAttributes['pa_polycarbonate'] = $attributes['pa_polycarbonate'];

$available_variations = array_values($product->get_available_variations());

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

?>

<style>

    .custom-page__body {
        font-family: "Arsenal-Regular", Arial, sans-serif;
        background-image: url("<?php echo get_stylesheet_directory_uri(); ?>/assets/images/background.jpg");
        background-size: contain;
        min-height: 950px;
        min-width: 1140px;
        position: relative;
    }

    .custom-page__main-image {
        width: 480px;
        float: left;
    }

    .image-in-container {
        width: 100%;
    }


    .custom-page__info-block {
        position: relative;
    }

    .custom-page__title {
        font-size: 30px;
        color: #6d6d6d;
    }


    .custom-page__product-name-container {
        display: flex;
        align-items: flex-start;
        flex-wrap: wrap;
    }

    .custom-page__certificates {
        position: absolute;
        top: 0;
        right: 0;
    }

    .custom-page__certificate {
        width: 50px;
        margin: 5px;
    }

    .custom-page__product-name {
        font-size: 55px;
        color: #140a00;
    }

    .custom-page__best-price-label {
        display: inline-block;
        color: #ff7f00;
        border-radius: 5px;
        border: 1px solid #ff7f00;
        text-transform: uppercase;
        padding: 2px 8px;
        vertical-align: middle;
        font-size: 14px;
        line-height: 18px;
    }

    .custom-page__dimension-attributes-container {
        padding-top: 50px;
        float: left;
    }

    .custom-page__dimension-attributes-title {
        font-size: 30px;
        line-height: 34px;
        color: #140a00;
    }

    .custom-page__dimension-attributes {
        margin-top: 25px;
    }

    .custom-page__dimension-attribute-title-block {
        margin-bottom: 15px;
        margin-top: 15px;
    }

    .custom-page__dimension-attribute-title {
        display: inline-block;
        font-weight: bold;
    }

    .custom-page__dimension-attribute-title-description {
        display: inline-block;
    }



    .custom-page__radio-btn {
        display: inline-block;
        margin-right: 10px;
    }
    .custom-page__radio-btn input[type=radio] {
        display: none;
    }
    .custom-page__radio-btn-label {
        display: inline-block;
        cursor: pointer;
        padding: 0px 15px;
        font-size: 25px;
        line-height: 25px;
        border-bottom: 1px #c1c1c1 dashed;
        user-select: none;
        padding: 6px 9px;
    }

    /* Checked */
    .custom-page__radio-btn input[type=radio]:checked + .custom-page__radio-btn-label {
        background: #eaeaea;
        -webkit-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        -moz-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        border-radius: 6px;
        border: 0px;
        color: #960f19;
        font-weight: bold;
    }

    /* Hover */
    .custom-page__radio-btn label:hover {
        color: #666;
    }

    /* Disabled */
    .custom-page__radio-btn input[type=radio]:disabled + .custom-page__radio-btn-label {
        background: #efefef;
        color: #666;
    }



    .custom-page__base-option-attributes-container {
        float: left;
        width: 900px;
        padding-top: 50px;
    }

    .custom-page__base-option-attributes {
        display: flex;
        align-items: flex-start;
        justify-content: flex-start;
        flex-wrap: wrap;
        margin: 0 -40px;
    }

    .custom-page__base-option-attribute {
        margin: 0 40px;
    }

    .custom-page__base-option-attribute-title {
        font-size: 30px;
        line-height: 34px;
        color: #645050;
        margin-bottom: 20px;
        width: 180px;
    }


    .custom-page__radio input[type=radio] {
        position: absolute;
        z-index: -1;
        opacity: 0;
    }

    .custom-page__radio .custom-page__radio-label {
        display: inline-block;
        cursor: pointer;
        position: relative;
        margin-right: 0;
        user-select: none;

        font-size: 25px;
        line-height: 29px;
        color: #140a00;
        font-weight: 400;
    }

    .custom-page__radio .custom-page__radio-label:before {
        content: '';
        display: inline-block;
        width: 19px;
        height: 19px;

        border: 0px solid #adb5bd;
        border-radius: 50%;
        margin-right: 0.5em;

        -webkit-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        -moz-box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
        box-shadow: 0px 5px 10px 2px rgba(34, 60, 80, 0.2) inset;
    }

    /* Checked */
    .custom-page__radio input[type=radio]:checked+.custom-page__radio-label:after {
        content: '';
        display: block;
        width: 9px;
        height: 9px;
        background: 0 0;
        border-radius: 50%;
        cursor: pointer;
        margin: 0;
        padding: 0;
        position: absolute;
        z-index: 2;
        left: 5px;
        top: 10px;
        -webkit-transition: background .4s ease;
        -ms-transition: background .4s ease;
        transition: background .4s ease;
        background: #8c1e14;
    }

    /* Hover */
    .custom-page__radio .custom-page__radio-label:hover:before {
        filter: brightness(120%);
    }

    /* Disabled */
    .custom-page__radio input[type=radio]:disabled + .custom-page__radio-label:before {
        filter: grayscale(100%);
    }


    .custom-page__order-form-container {
        -webkit-box-shadow: -2px 9px 13px 3px rgba(34, 60, 80, 0.41);
        -moz-box-shadow: -2px 9px 13px 3px rgba(34, 60, 80, 0.41);
        box-shadow: -2px 9px 13px 3px rgba(34, 60, 80, 0.41);

        border-radius: 15px;
        width: 380px;
        position: absolute;
        top: 250px;
        right: 0px;
        padding: 20px;
    }

    .custom-page__calc-menu {
        border-radius: 15px;
        padding: 15px;
        background: #ffdc28;
        margin-bottom: 50px;

        -webkit-box-shadow: -2px 9px 13px -4px rgba(255, 220, 40, 0.17);
        -moz-box-shadow: -2px 9px 13px -4px rgba(255, 220, 40, 0.17);
        box-shadow: -2px 9px 13px -4px rgba(255, 220, 40, 0.17);
    }

    .custom-page__calc-menu-title {
        font-size: 18px;
        line-height: 22px;
        color: #140a00;
        margin-bottom: 10px;
    }

    .custom-page__calc-menu-total {
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .custom-page__calc-menu-total-base {
        white-space: nowrap;
        font-size: 74px;
        line-height: 74px;
        font-weight: bold;
        color: #140a00;
    }

    .custom-page__calc-menu-total-currency {
        font-size: 37px;
        font-weight: 700;
        margin-left: 15px;
    }

    .custom-page__calc-menu-price-summary-title {
        font-weight: bold;
        margin-top: 30px;
        margin-bottom: 15px;
    }

    .custom-page__calc-menu-price-summary-list {
        font-size: 15px;
        line-height: 15px;
    }

    .custom-page__calc-menu-price-summary-list ul {
        margin-left: 18px;
    }

    .custom-page__calc-menu-price-summary-list ul li{
        margin: 0px;
        line-height: 17px;
    }

    input[type=text]
    {
        font-size: 25px!important;
        line-height: 29px!important;
        color: #000000 !important;
        background: rgb(255, 255, 255) !important;
        border-radius: 10px!important;
        font-weight: normal!important;
        text-transform: none!important;
        height: 50px;
        padding: 2px 10px!important;
        margin: 10px 0px;
        border: 1px solid #d2d7d7;

        -webkit-box-shadow: -2px 6px 29px 19px rgba(86, 86, 86, 0.17);
        -moz-box-shadow: -2px 6px 29px 19px rgba(86, 86, 86, 0.17);
        box-shadow: -2px 6px 29px 19px rgba(86, 86, 86, 0.17);
    }


    ::-webkit-input-placeholder, { /* Edge */
        color: #d2d7d7;
        font-weight: normal!important;
        font-size: 18px!important;
        line-height: 20px!important;
    }

    :-ms-input-placeholder { /* Internet Explorer 10-11 */
        color: #d2d7d7!important;
        font-weight: normal!important;
        font-size: 18px!important;
        line-height: 20px!important;
    }

    ::placeholder {
        color: #d2d7d7!important;
        font-weight: normal!important;
        font-size: 18px!important;
        line-height: 20px!important;
    }


    input[type=submit] {
        font-size: 25px!important;
        line-height: 29px!important;
        color: white!important;
        background: rgba(131,185,76,1)!important;
        border-radius: 10px!important;
        font-weight: normal!important;
        text-transform: none!important;
        height: 50px;
        padding: 2px 10px!important;
        margin: 5px 0px;

        -webkit-box-shadow: -2px 9px 13px -4px rgba(61, 221, 122, 0.17);
        -moz-box-shadow: -2px 9px 13px -4px rgba(61, 221, 122, 0.17);
        box-shadow: -2px 9px 13px -4px rgba(61, 221, 122, 0.17);
    }

    input[type=submit]:hover {
        text-decoration: none!important;
    }


</style>

<div class="custom-page__body">
    <input id = 'product-id' type="hidden" value="<?=$product->get_id();?>">
    <div class="custom-page__main-image">
        <img class="image-in-container" src="<?= $imagePath ?>">
    </div>
    <div class="custom-page__info-block">
        <div class="custom-page__title">
            Расчет стоимости теплицы
        </div>
        <div class="custom-page__product-name-container">
            <div class="custom-page__product-name">
                <?= $productName ?>
            </div>
            <div class="custom-page__best-price-label">
                Лучшая цена
            </div>
        </div>
        <div class="custom-page__certificates">
            <div class="custom-page__certificate">
                <img class="image-in-container" src="https://xn--m1ao.xn--p1ai/local/templates/kreml/img/nf_rst.svg">
            </div>
            <div class="custom-page__certificate">
                <img class="image-in-container" src="https://xn--m1ao.xn--p1ai/local/templates/kreml/img/nf_gost.svg">
            </div>
        </div>
        <div class="custom-page__description">
            <?= $description ?>
        </div>
    </div>
    <div class="custom-page__dimension-attributes-container">
        <div class="custom-page__dimension-attributes-title">Габариты теплицы</div>
        <div class="custom-page__dimension-attributes">
            <?php foreach ($dimensionsAttributes as $attribute_name => $options) :

            $attributeName = wc_attribute_label($attribute_name);
            $titleDescription = '';
            $explodedAttributeName = explode(':', $attributeName);
            if(count($explodedAttributeName) >= 2) {
                $attributeName = $explodedAttributeName[0] . ':';
                $titleDescription = $explodedAttributeName[1];
            }
            ?>
                <div class="custom-page__dimension-attribute">
                    <div class="custom-page__dimension-attribute-title-block">
                        <div class="custom-page__dimension-attribute-title">
                            <?= wc_attribute_label( $attributeName ); ?>
                        </div>
                        <div class="custom-page__dimension-attribute-title-description">
                            <?= wc_attribute_label( $titleDescription ); ?>
                        </div>
                    </div>

                    <?php

                    $terms = wc_get_product_terms(
                        $product->get_id(),
                        $attribute_name,
                        array(
                            'fields' => 'all',
                        )
                    );

                    foreach ($terms as $term) :
                        $checked = '';

                        if ($default_attributes[$term->taxonomy] === $term->slug) {
                            $checked = 'checked';
                        }
                        ?>

                        <div class="custom-page__radio-btn">
                            <input id="<?= esc_attr($term->taxonomy.'_'.$term->term_id); ?>" type="radio" name="<?= esc_attr($term->taxonomy); ?>" value="<?=esc_attr( $term->slug )?>" <?= $checked?>>
                            <label class="custom-page__radio-btn-label" for="<?= esc_attr($term->taxonomy.'_'.$term->term_id); ?>">
                                <?= esc_attr($term->name); ?>
                            </label>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <div class="custom-page__base-option-attributes-container">
        <div class="custom-page__base-option-attributes">
            <?php foreach ($baseOptionsAttributes as $attribute_name => $options) :
                $attributeName = wc_attribute_label($attribute_name);
                ?>
                <div class="custom-page__base-option-attribute">
                    <div class="custom-page__base-option-attribute-title">
                        <?= $attributeName ?>
                    </div>

                <?php

                $terms = wc_get_product_terms(
                    $product->get_id(),
                    $attribute_name,
                    array(
                        'fields' => 'all',
                    )
                );

                foreach ($terms as $term) :
                    $checked = '';

                    if ($default_attributes[$term->taxonomy] === $term->slug) {
                        $checked = 'checked';
                    }
                    ?>

                    <div class="custom-page__radio">
                        <input id="<?= esc_attr($term->taxonomy.'_'.$term->term_id); ?>" type="radio" name="<?= esc_attr($term->taxonomy); ?>" value="<?=esc_attr( $term->slug )?>" <?= $checked?>>
                        <label class="custom-page__radio-label" for="<?= esc_attr($term->taxonomy.'_'.$term->term_id); ?>">
                            <?= esc_attr($term->name); ?>
                        </label>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="custom-page__order-form-container">
        <div class="custom-page__calc-menu">
            <div class="custom-page__calc-menu-title">
                Стоимость с учетом выбранных опций
            </div>
            <div class="custom-page__calc-menu-total">
                <div class="custom-page__calc-menu-total-base">
                    23 980
                </div>
                <div class="custom-page__calc-menu-total-currency">
                    руб.
                </div>
            </div>
            <div class="custom-page__calc-menu-price-summary">
                <div class="custom-page__calc-menu-price-summary-title">
                    Вы выбрали:
                </div>
                <div class="custom-page__calc-menu-price-summary-list">
                    <ul>
                        <li>
                            Теплица: Репка Двушка 2м х 8м 31 060 р.
                        </li>
                        <li>
                            Защита каркаса: Цинк
                        </li>
                        <li>
                            Сотовый поликарбонат: Полигаль-Киви
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <input type="text" placeholder="Как к Вам обращаться?">
        <input id="#phone" type="text" placeholder="Номер телефона">
        <input type="submit" value="Отправить заявку">
    </div>
</div>


<script>

    let width = 0;
    let length = 0;
    let constructionType = '';
    let frameProtection = '';
    let polycarbonate = '';
    let productId = 0;

    bindWidthSelector();
    bindLengthSelector();
    bindConstructionTypeSelector();
    bindFrameProtectionSelector();
    bindPolycarbonateSelector();
    bindProductId();

    function bindWidthSelector(){

        let widthButtons = document.querySelectorAll('.custom-page__radio-btn input[name=pa_width]');

        let i;
        for (i = 0; i < widthButtons.length; ++i) {

            if (widthButtons[i].checked) {
                setWidth(widthButtons[i].value);
            }

            widthButtons[i].addEventListener("change", function (e) {
                setWidth(e.target.value);
                getVariation();
            });
        }
    }
    function setWidth(newValue) {
        width = newValue;
        console.log('Width changed: ' + newValue);
    }

    function bindLengthSelector(){

        let existedElements = document.querySelectorAll('.custom-page__radio-btn input[name=pa_length]');

        let i;
        for (i = 0; i < existedElements.length; ++i) {

            if (existedElements[i].checked) {
                setLength(existedElements[i].value);
            }

            existedElements[i].addEventListener("change", function (e) {
                setLength(e.target.value);
                getVariation();
            });
        }
    }
    function setLength(newValue) {
        length = newValue;
        console.log('Length changed: ' + length);
    }

    function bindConstructionTypeSelector(){

        let existedElements = document.querySelectorAll('.custom-page__radio input[name=pa_construction_type]');

        let i;
        for (i = 0; i < existedElements.length; ++i) {

            if (existedElements[i].checked) {
                setConstructionType(existedElements[i].value);
            }

            existedElements[i].addEventListener("change", function (e) {
                setConstructionType(e.target.value);
                getVariation();
            });
        }
    }
    function setConstructionType(newValue) {
        constructionType = newValue;
        console.log('constructionType changed: ' + constructionType);
    }

    function bindFrameProtectionSelector(){

        let existedElements = document.querySelectorAll('.custom-page__radio input[name=pa_frame_protection]');

        let i;
        for (i = 0; i < existedElements.length; ++i) {

            if (existedElements[i].checked) {
                setFrameProtection(existedElements[i].value);
            }

            existedElements[i].addEventListener("change", function (e) {
                setFrameProtection(e.target.value);
                getVariation();
            });
        }
    }
    function setFrameProtection(newValue) {
        frameProtection = newValue;
        console.log('frameProtection changed: ' + frameProtection);
    }

    function bindPolycarbonateSelector(){

        let existedElements = document.querySelectorAll('.custom-page__radio input[name=pa_polycarbonate]');

        let i;
        for (i = 0; i < existedElements.length; ++i) {

            if (existedElements[i].checked) {
                setPolycarbonate(existedElements[i].value);
            }

            existedElements[i].addEventListener("change", function (e) {
                setPolycarbonate(e.target.value);
                getVariation();
            });
        }
    }
    function setPolycarbonate(newValue) {
        polycarbonate = newValue;
        console.log('polycarbonate changed: ' + polycarbonate);
    }

    function bindProductId(){

        let existedElement = document.getElementById('product-id');
        setProductId(existedElement.value);
    }
    function setProductId(newValue) {
        productId = newValue;
        console.log('productId changed: ' + productId);
    }




    function getVariation() {

        let formData = new FormData();
        formData.append("attribute_pa_width", width);
        formData.append("attribute_pa_length", length);
        formData.append("attribute_pa_construction_type", constructionType);
        formData.append("attribute_pa_frame_protection", frameProtection);
        formData.append("attribute_pa_polycarbonate", polycarbonate);
        formData.append("product_id", productId);

        var request = new XMLHttpRequest();


        // request.addEventListener("progress", updateProgress, false);
        request.addEventListener("load", transferComplete, false);
        request.addEventListener("error", transferFailed, false);
        // request.addEventListener("abort", transferCanceled, false);


        // состояние передачи от сервера к клиенту (загрузка)
        function updateProgress (oEvent) {
            if (oEvent.lengthComputable) {
                var percentComplete = oEvent.loaded / oEvent.total;
                // ...
            } else {
                // Невозможно вычислить состояние загрузки, так как размер неизвестен
            }
        }

        function transferComplete(evt) {
            var result = JSON.parse(request.response);
            let imagePath = result.image.url;

            setImage(imagePath);
        }

        function transferFailed(evt) {
            alert("При загрузке файла произошла ошибка.");
        }

        function transferCanceled(evt) {
            alert("Пользователь отменил загрузку.");
        }


        request.open("POST", "/?wc-ajax=get_variation");
        request.send(formData);
    }

    function setImage(value) {

        var imageContainerOpt = document.getElementsByClassName('image-in-container');
        if (imageContainerOpt.length > 0) {
            imageContainerOpt[0].setAttribute('src', value);
        }
    }

</script>