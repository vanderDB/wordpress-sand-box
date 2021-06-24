<?php

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;

const PA_CONSTRUCTION_TYPE = 'pa_construction_type';
const PA_POLYCARBONATE = 'pa_polycarbonate';
const PA_WIDTH = 'pa_width';
const PA_LENGTH = 'pa_length';
const PA_FRAME_PROTECTION = 'pa_frame_protection';



$termsForConstructionType = getTermInfo($product->get_id(), PA_CONSTRUCTION_TYPE);
$termsForPolycarbonate = getTermInfo($product->get_id(), PA_POLYCARBONATE);
$termsForFrameProtection = getTermInfo($product->get_id(), PA_FRAME_PROTECTION);
$termsForWidth = getTermInfo($product->get_id(), PA_WIDTH);
$termsForLength = getTermInfo($product->get_id(), PA_LENGTH);

function getTermInfo($productId, $attributeMarker): array
{

    return wc_get_product_terms(
        $productId,
        $attributeMarker,
        array(
            'fields' => 'all',
        )
    );
}

$default_attributes = $product->get_default_attributes();

$attributes = $product->get_variation_attributes();

$dimensionsAttributes = [];
if (isset($attributes[PA_WIDTH]))
    $dimensionsAttributes[PA_WIDTH] = $attributes[PA_WIDTH];
if (isset($attributes[PA_LENGTH]))
    $dimensionsAttributes[PA_LENGTH] = $attributes[PA_LENGTH];

$baseOptionsAttributes = [];
if (isset($attributes[PA_CONSTRUCTION_TYPE]))
    $baseOptionsAttributes[PA_CONSTRUCTION_TYPE] = $attributes[PA_CONSTRUCTION_TYPE];
if (isset($attributes[PA_FRAME_PROTECTION]))
    $baseOptionsAttributes[PA_FRAME_PROTECTION] = $attributes[PA_FRAME_PROTECTION];
if (isset($attributes[PA_POLYCARBONATE]))
    $baseOptionsAttributes[PA_POLYCARBONATE] = $attributes[PA_POLYCARBONATE];

?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Arsenal&display=swap');

    .custom-page__global-loader-box {
        display: flex;
        align-items: center;
        justify-content: center;
        position: fixed;
        width: 100vw;
        height: 100vH;
        background: rgba(0,0,0,0.5);
        border-radius: 15px;

        top: 0px;
        left: 0px;
        z-index: 9999;
    }

    .custom-page__global-loader {
        border: 20px solid #f3f3f3;
        border-radius: 50%;
        border-top: 20px solid #3498db;
        width: 150px;
        height: 150px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    .custom-page__body {
        font-family: "Arsenal", Arial, sans-serif!important;
        width: 100%;
        position: relative;
        display: none;
    }

    .custom-page__main-image {
        width: 450px;
        float: left;
        padding: 15px;
        margin-right: 15px;
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
        margin-top: 20px;
        margin-bottom: 20px;
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
        line-height: 55px;
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
        margin-left: 15px;
    }

    .custom-page__description {
        font-size: 17px;
        line-height: 23px;
        color: #140a00;
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
        font-size: 17px;
        line-height: 21px;
        color: #140a00;
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
        top: 280px;
        right: 0px;
        padding: 20px;
    }

    .custom-page__calc-menu {
        border-radius: 15px;
        padding: 15px;
        background: #ffdc28;
        margin-bottom: 50px;
        position: relative;

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
        color: #140a00;
    }

    .custom-page__calc-menu-price-summary-title {
        font-weight: bold;
        margin-top: 30px;
        margin-bottom: 15px;
        font-size: 20px;
        line-height: 24px;
        color: #140a00;
    }

    .custom-page__calc-menu-price-summary-list {
        font-size: 15px;
        line-height: 15px;
        color: #140a00;
    }

    .custom-page__calc-menu-price-summary-list ul {

    }

    .custom-page__calc-menu-price-summary-list ul li{
        margin: 0px;
        line-height: 19px;
    }

    .custom-page__summary-name-size-price-part-two {
        font-weight: bold;
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




    .loader-box {
        align-items: center;
        justify-content: center;
        position: absolute;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        border-radius: 15px;
        margin-left: -15px;
        margin-top: -15px;

        display: none;
    }

    .loader {
        border: 10px solid #f3f3f3;
        border-radius: 50%;
        border-top: 10px solid #3498db;
        width: 50px;
        height: 50px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    /* Safari */
    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

</style>

<body onload="getVariation();">
<div class="custom-page__global-loader-box">
    <div class="custom-page__global-loader">

    </div>
</div>
<div class="custom-page__body">
    <input id='product-id' type="hidden" value="<?=$product->get_id();?>">
    <input id='variation-id' type="hidden" value="<?=$product->get_id();?>">

    <div class="custom-page__main-image">
        <img class="image-in-container">
    </div>
    <div class="custom-page__info-block">
        <div class="custom-page__title">
            Расчет стоимости теплицы
        </div>
        <div class="custom-page__product-name-container">
            <div class="custom-page__product-name">

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

                    $terms = getTermInfo($product->get_id(), $attribute_name);

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

                $terms = getTermInfo($product->get_id(), $attribute_name);

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

            <div class="loader-box">
                <div class="loader"></div>
            </div>

            <div class="custom-page__calc-menu-title">
                Стоимость с учетом выбранных опций
            </div>
            <div class="custom-page__calc-menu-total">
                <div class="custom-page__calc-menu-total-base">

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
                        <li class="custom-page__summary-name-size-price">
                            <span class="custom-page__summary-name-size-price-part-one"></span>
                            <span class="custom-page__summary-name-size-price-part-two"></span>
                        </li>
                        <li class="custom-page__summary-frame-protection"></li>
                        <li class="custom-page__summary-polycarbonate"></li>
                    </ul>
                </div>
            </div>
        </div>

<!--        <input type="text" placeholder="Как к Вам обращаться?">-->
<!--        <input id="#phone" type="text" placeholder="Номер телефона">-->
        <input type="submit" onclick="addVariationToCart(event);" value="Добавить в корзину">

    </div>
</div>
</body>


<script>

    let defaultProductDescription = '<?= $product->get_description(); ?>';

    let availConstructionTypes = JSON.parse('<?= json_encode($termsForConstructionType) ?>');
    let availWidths = JSON.parse('<?= json_encode($termsForWidth) ?>');
    let availLength = JSON.parse('<?= json_encode($termsForLength) ?>');
    let availFrameProtections = JSON.parse('<?= json_encode($termsForFrameProtection) ?>');
    let availPolycarbonate = JSON.parse('<?= json_encode($termsForPolycarbonate) ?>');


    let width = 0;
    let length = 0;
    let constructionType = '';
    let frameProtection = '';
    let polycarbonate = '';
    let productId = '<?= $product->get_id(); ?>';
    let variationId = 0;

    bindWidthSelector();
    bindLengthSelector();
    bindConstructionTypeSelector();
    bindFrameProtectionSelector();
    bindPolycarbonateSelector();
    //bindProductId();

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
    }


    const loaderClass = 'custom-page__global-loader-box';

    function showLoader() {

        var loaderBox = document.getElementsByClassName(loaderClass);
        if (loaderBox.length>0) {
            loaderBox[0].style.display = "flex";
        }
    }

    function hideLoader() {

        var loaderBox = document.getElementsByClassName(loaderClass);
        if (loaderBox.length>0) {
            loaderBox[0].style.display = "none";
        }
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

            refreshUIVariationData(result);
            hideLoader();
        }

        function transferFailed(evt) {
            alert("При загрузке файла произошла ошибка.");
        }

        function transferCanceled(evt) {
            alert("Пользователь отменил загрузку.");
        }


        request.open("POST", "/?wc-ajax=get_variation");
        showLoader();
        request.send(formData);
    }

    function addVariationToCart(event) {

        event.preventDefault();

        let formData = new FormData();
        formData.append("product_id", productId);
        formData.append("product_sku", "");
        formData.append("quantity", 1);
        formData.append("variation_id", variationId);
        formData.append("action", 'woocommerce_ajax_add_to_cart');

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

            alert("Товар добавлен в корзину.");
            hideLoader();

            location.reload();
        }

        function transferFailed(evt) {
            alert("При загрузке файла произошла ошибка.");
        }

        function transferCanceled(evt) {
            alert("Пользователь отменил загрузку.");
        }


        request.open("POST", `?add-to-cart=${productId}`);
        showLoader();
        request.send(formData);
    }

    function refreshUIVariationData(result) {

        let customPageBodies = document.getElementsByClassName('custom-page__body');
        if (customPageBodies.length > 0) {
            customPageBodies[0].style.display = 'block';
        }

        let globalLoaders = document.getElementsByClassName('custom-page__global-loader-box');
        if (globalLoaders.length > 0) {
            globalLoaders[0].style.display = 'none';
        }

        refreshImage(result);
        refreshPrice(result);
        refreshTitle(result);
        refreshDescription(result);
        refreshSummary(result);
        refreshVariationId(result);

        console.log(0);
        console.log(productId);
        console.log(variationId);
    }

    function refreshImage(result) {
        let imagePath = result.image.url;
        setImage(imagePath);
    }

    function refreshPrice(result) {
        let newValue = getPriceFromResult(result);
        setPrice(newValue);
    }

    function refreshTitle(result) {

        let newValue = getAttributeValueBySlugFromResult(result, availConstructionTypes, 'attribute_pa_construction_type');

        setTitle(newValue);
    }

    function refreshDescription(result) {

        let newValue = result.variation_description;
        if (newValue == undefined || newValue === '') {
            newValue = defaultProductDescription;
        }

        setDescription(newValue);
    }

    function refreshSummary(result) {

        let constructionTypeValue = getAttributeValueBySlugFromResult(result, availConstructionTypes, 'attribute_pa_construction_type');
        let widthValue = getAttributeValueBySlugFromResult(result, availWidths, 'attribute_pa_width');
        let lengthValue = getAttributeValueBySlugFromResult(result, availLength, 'attribute_pa_length');
        let priceValue = getPriceFromResult(result);
        let frameProtectionValue = getAttributeValueBySlugFromResult(result, availFrameProtections, 'attribute_pa_frame_protection');
        let polycarbonateValue = getAttributeValueBySlugFromResult(result, availPolycarbonate, 'attribute_pa_polycarbonate');

        let nameSizeString = `Теплица: ${constructionTypeValue} ${widthValue} х ${lengthValue}`;
        let priceString = `${priceValue} р.`;
        let frameProtectionString = `Защита каркаса: ${frameProtectionValue}`;
        let polycarbonateString = `Сотовый поликарбонат: ${polycarbonateValue}`;

        setSummaryNameSize(nameSizeString);
        setSummaryPrice(priceString);
        setSummaryFrameProtection(frameProtectionString);
        setSummaryPolycarbonate(polycarbonateString);
    }

    function refreshVariationId(result) {

        let newValue = result.variation_id;
        setVariationId(newValue);
    }


    function setImage(value) {

        var imageContainerOpt = document.getElementsByClassName('image-in-container');
        if (imageContainerOpt.length > 0) {
            imageContainerOpt[0].setAttribute('src', value);
        }
    }
    function setPrice(value) {
        var elements = document.getElementsByClassName('custom-page__calc-menu-total-base');
        if (elements.length > 0) {
            elements[0].innerText = value;
        }
    }
    function setTitle(value) {

        let elements = document.getElementsByClassName('custom-page__product-name');
        if (elements.length > 0) {
            elements[0].innerText = value;
        }
    }
    function setDescription(value) {

        let elements = document.getElementsByClassName('custom-page__description');
        if (elements.length > 0) {
            elements[0].innerHTML = value;
        }
    }
    function setSummaryNameSize(value) {
        let elements = document.getElementsByClassName('custom-page__summary-name-size-price-part-one');
        if (elements.length > 0) {
            elements[0].innerHTML = value;
        }
    }
    function setSummaryPrice(value) {
        let elements = document.getElementsByClassName('custom-page__summary-name-size-price-part-two');
        if (elements.length > 0) {
            elements[0].innerHTML = value;
        }
    }
    function setSummaryFrameProtection(value) {
        let elements = document.getElementsByClassName('custom-page__summary-frame-protection');
        if (elements.length > 0) {
            elements[0].innerHTML = value;
        }
    }
    function setSummaryPolycarbonate(value) {
        let elements = document.getElementsByClassName('custom-page__summary-polycarbonate');
        if (elements.length > 0) {
            elements[0].innerHTML = value;
        }
    }
    function setVariationId(value) {

        variationId = value;
    }



    function getAttributeValueBySlugFromResult(result, availAttributes, attributeName) {

        let slug = result.attributes[attributeName];
        let value = 'Н/Д';

        availAttributes.forEach(element => {
            if (slug === element.slug) {
                value = element.name;
            }
        });

        return value;
    }
    function getPriceFromResult(result) {
        let newValue = addSpacesToPrice(result.display_price);

        return newValue;
    }
    function getVariationIdFromResult(result) {

        return result.variation_id;
    }



    function addSpacesToPrice(price) {
        price = parseInt(price).toLocaleString('ru-RU');
        return price;
    }

</script>