<!DOCTYPE html>
<html amp>
<head>
    <meta charset="UTF-8">
    <title><?= $title; ?> | Форум</title>
    <meta
      name="viewport"
      content="width=device-width,minimum-scale=1,initial-scale=1"
    />
    <meta name="keywords" content="Вопросы, форум<?php if (isset($gif["nameCat"])): ?>, <?= $gif["nameCat"]; ?><?php endif; ?>, <?= $title; ?>, сайт 'Форум'">
    <meta name="description" content="<?= $title; ?> на сайте 'Форум'">
    <script type="application/ld+json">
    {
      "@context": "http://schema.org",
        "@type": "Category",
        "headline": "Category headline",
        "image": ["/img/favicon.ico"]"
    }
    </script>
    <link rel="shortcut icon" type="image/x-icon" href="/img/favicon.ico">
    <link rel="canonical" href="<?= $href; ?>">
    <style>
    amp-boilerplate>body{-webkit-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-moz-animation:-amp-start 8s steps(1,end) 0s 1 normal both;-ms-animation:-amp-start 8s steps(1,end) 0s 1 normal both;animation:-amp-start 8s steps(1,end) 0s 1 normal both}@-webkit-keyframes
      -amp-start{from{visibility:hidden}to{visibility:visible}}@-moz-keyframes
      -amp-start{from{visibility:hidden}to{visibility:visible}}@-ms-keyframes
      -amp-start{from{visibility:hidden}to{visibility:visible}}@-o-keyframes
      -amp-start{from{visibility:hidden}to{visibility:visible}}@keyframes
      -amp-start{from{visibility:hidden}to{visibility:visible}}
    </style>
    <noscript><style amp-boilerplate>body{-webkit-animation:none;-moz-animation:none;-ms-animation:none;animation:none}</style></noscript>
    <style amp-custom>
      @font-face {
        font-weight: 400;
        font-family: "Roboto";
        font-style: normal;
        src: url("/fonts/roboto-regular.woff2") format("woff2"), url("/fonts/roboto-regular.woff") format("woff");
      }

      @font-face {
        font-weight: 700;
        font-family: "Roboto";
        font-style: normal;
        src: url("/fonts/roboto-bold.woff2") format("woff2"), url("/fonts/roboto-bold.woff") format("woff");
      }
      .rating {
          width: 160px;
          border-radius: 4px;
          box-shadow: 0 0 2px 1px #333333;
          margin: 10px auto;
          padding: 10px 20px;
          text-align: center;
      }
      .star {
          float: left;
          width: 30px;
          height: 30px;
          margin: 0;
          cursor: pointer;
          background: url(/rating/star.png);
      }
      .star2 {
          background: url(/rating/star2.png)!important;
      }
      .star3 {
          background: url(/rating/star2.png);
      }
      #star_message { text-align: center; }
      .star_rating, #star_rating, #star_votes, #star_message {
          color: black;
      }
      .star_rating {
            width: fit-content;
          position: relative;
          padding-right: 20px;
      }
      .star_rating::after {
          content: "";
          position: absolute;
          right: 0;
          width: 15px;
          height: 15px;
          background-image: url(/rating/star2.png);
          background-repeat: no-repeat;
          background-position: 0 0;
          background-size: cover;
      }
      .rating:hover .star3 {
        background: url(/rating/star.png);
      }
      #success-response {
        color: green;
          position: absolute;
          top: 78%;
          left: 50%;
      }
      .star:hover {
        background: url(/rating/star2.png)!important;
      }
      .star:hover ~ .star {
        background: url(/rating/star2.png)!important;
      }
      .rating_wrapper {
        display: flex;
        flex-direction: row-reverse;
      }




      .visually-hidden {
        position: absolute;

        width: 1px;
        height: 1px;
        margin: -1px;
        padding: 0;
        overflow: hidden;

        border: 0;

        clip: rect(0, 0, 0, 0);
        clip-path: inset(100%);
      }

      body {
        min-width: 1340px;

        font-size: 16px;
        font-family: "Roboto", sans-serif;
        color: #ffffff;

        background-color: #3a4153;
        background-image: url("/img/background-pattern.svg");
        background-position: -460px -75px;
        background-size: 1920px;
      }

      .container {
        width: 1300px;
        margin: 0 auto;
        padding: 0 20px;
      }
      .gif__picture {
        color:black;
      }

      .button {
        display: inline-block;
        width: 195px;
        padding: 16px 0;

        font-weight: 700;
        font-size: 18px;
        text-align: center;
        color: inherit;
        text-transform: uppercase;
        text-decoration: none;

        background-color: #5ac000;
        border: 0;
        border-radius: 30px;
      }

      .button:hover,
      .button:focus {
        background-color: #65d600;
      }

      .button:active {
        color: rgba(255, 255, 255, 0.3);

        background-color: #53b100;
      }

      .button--transparent {
        font-weight: 400;
        color: #444444;

        background-color: transparent;
        border: 1px solid #e5e5e5;
      }

      .button--transparent:hover,
      .button--transparent:focus {
        background-color: transparent;
        border-color: #d3d3d3;
      }

      .button--transparent:active {
        color: rgba(0, 0, 0, 0.3);

        border-color: #f2f2f2;
      }

      .button--transparent-thick {
        text-transform: none;

        border: 4px solid #5ac000;
      }

      .button--transparent-thick:hover,
      .button--transparent-thick:focus {
        color: #ffffff;

        background-color: #65d600;
        border-color: #65d600;
      }

      .button--transparent-thick:active {
        background-color: #53b100;
        border-color: #53b100;
      }

      .main-header {
        display: flex;
        margin-bottom: 34px;
        padding-top: 36px;
      }

      .logo {
        position: relative;

        margin-right: 95px;

        border: 6px solid #535869;
        border-radius: 5px;
      }

      .logo::before {
        content: "";
        position: absolute;
        top: -10px;
        left: 50px;

        width: 11px;
        height: 11px;

        background-color: #ffffff;
        border-radius: 50%;
      }

      .logo[href]:hover,
      .logo[href]:focus {
        background-color: #535869;
      }

      .logo[href]:active {
        background-color: transparent;
        border-color: transparent;
      }

      .logo__img {
        padding: 7px 9px;
      }

      .search {
        width: 900px;
      }

      .search__control {
        position: relative;

        display: flex;
        height: 52px;
        padding: 7px;
      }

      .search__control:placeholder-shown:hover .search__submit::after {
        background-color: #021628;
        border-color: #021628;
      }

      .search__text {
        position: relative;
        z-index: 1;

        width: 628px;
        margin-right: 10px;
        padding-left: 52px;

        font-size: 18px;
        color: inherit;

        background-color: transparent;
        background-image: url("/img/search.svg");
        background-repeat: no-repeat;
        background-position: 16px 50%;
        border: 0;
        outline: none;
      }

      .search__text:placeholder-shown + .search__submit::after {
        background-color: #061e33;
      }

      .search__text:focus + .search__submit::after {
        background-color: transparent;
      }

      .search__submit {
        position: relative;

        width: 195px;
      }

      .search__submit::after {
        content: "";
        position: absolute;
        top: -7px;
        left: -698px;

        width: 886px;
        height: 52px;

        border: 7px solid #061e33;
        border-radius: 50px;
      }

      .search__submit .button {
        position: absolute;
        top: 50%;
        left: 50%;
        z-index: 1;

        border-radius: 40px;
        transform: translate(-50%, -50%);

        transition: 0.1s;
      }

      .search__submit .button:hover,
      .search__submit .button:focus {
        width: 210px;
        padding: 23px;
      }

      .main-content {
        display: flex;
        margin-bottom: 76px;
      }

      .navigation {
        width: 180px;
        padding: 0 20px;

        color: #ffffff;
      }

      .navigation__item {
        margin-bottom: 45px;
        padding-top: 6px;

        border-top: 1px solid rgba(255, 255, 255, 0.1);
      }

      .navigation__title {
        position: relative;

        margin: 10px 0 28px;
        padding-left: 14px;

        font-size: 12px;
        color: #616775;
        text-transform: uppercase;
      }

      .navigation__title::before {
        content: "";
        position: absolute;
        left: -4px;

        background-repeat: no-repeat;
        background-position: 0 0;
      }

      .navigation__title--account::before {
        width: 11px;
        height: 11px;

        background-image: url("/img/user.svg");
      }

      .navigation__title--list::before {
        width: 11px;
        height: 9px;

        background-image: url("/img/list.svg");
      }

      .navigation__title--list + .navigation__links a {
        margin-bottom: 10px;
      }

      .navigation__links {
        display: inline-flex;
        flex-direction: column;
        margin-left: -5px;
      }

      .navigation__links a {
        margin-bottom: 4px;
        padding: 5px;

        color: inherit;
        text-decoration: none;
      }

      .navigation__links a:hover,
      .navigation__links a:focus {
        opacity: 0.6;
      }

      .navigation__links a:active {
        opacity: 0.3;
      }

      .content {
        display: flex;
        width: 860px;
        padding: 43px 100px 100px;

        background-color: #ffffff;
      }

      .content__header {
        display: flex;
        margin-bottom: 66px;
      }

      .content__header--left-pad {
        margin-bottom: 53px;
        padding-left: 100px;
      }

      .content__header > :first-child {
        flex-grow: 1;
        margin-right: 10px;
      }

      .content__header-text {
        margin: 0;

        font-weight: 400;
        font-size: 40px;
        line-height: 1.2;
        color: #444444;
      }

      .content__header-button {
        margin-right: -4px;
      }

      .content__main-col {
        width: 100%;
      }

      .content__main-col + .content__additional-col {
        width: 374px;
        margin-top: -43px;
        margin-right: -100px;
        margin-bottom: -100px;
        margin-left: 20px;
      }

      .content__additional-col {
        padding: 40px 37px;

        background-color: #f2f2f2;
      }

      .content__additional-title {
        margin-bottom: 80px;

        font-weight: 400;
        font-size: 18px;
        color: #444444;
      }

      .content__load-button {
        width: 100%;
        padding: 32px;

        font-size: 18px;
        text-align: center;
        color: #444444;
        text-indent: 40px;

        background-color: transparent;
        border: 1px solid #e5e5e5;
      }

      .content__load-button:hover,
      .content__load-button:focus {
        color: #000000;

        border-color: #d0d0d0;
      }

      .content__load-button:hover span::before,
      .content__load-button:focus span::before {
        opacity: 0.6;
      }

      .content__load-button:active {
        color: rgba(0, 0, 0, 0.3);

        border-color: #f1f1f1;
      }

      .content__load-button:active span::before {
        opacity: 0.2;
      }

      .content__load-button span {
        position: relative;
      }

      .content__load-button span::before {
        content: "";
        position: absolute;
        left: -50px;

        width: 22px;
        height: 22px;

        background-image: url("/img/plus.svg");
        background-repeat: no-repeat;
        background-position: 0 0;
        opacity: 0.4;
      }

      .filter {
        display: flex;

        font-size: 18px;
      }

      .filter__item {
        margin-right: 41px;
        padding: 15px 0;

        color: #444444;
        text-decoration: none;

        opacity: 0.6;
      }

      .filter__item:hover,
      .filter__item:focus {
        opacity: 0.7;
      }

      .filter__item--active,
      .filter__item--active:hover,
      .filter__item--active:focus {
        border-bottom: 2px solid #007af5;
        opacity: 1;
      }

      .gif-list {
        display: flex;
        flex-wrap: wrap;
        margin: 0 0 17px;
        padding: 0;

        list-style: none;
      }

      .gif-list--vertical .gif-list__item {
        margin-right: 0;
        margin-bottom: 30px;
      }

      .gif {
        position: relative;

        width: 260px;
      }

      .gif .gif__picture {
        width: 100%;
        height: 260px;
        overflow: hidden;
      }

      .gif--large {
        width: 520px;
      }

      .gif--large .gif__picture {
        max-height: 520px;
        margin-bottom: 19px;
      }

      .gif--large .gif__picture label::before {
        width: 160px;
        height: 160px;
      }

      .gif--large .gif__picture label::after {
        width: 180px;
        height: 180px;

        background-size: 180px;
      }

      .gif--large .gif__desctiption {
        margin-bottom: 23px;
      }

      .gif--small {
        width: 200px;
      }

      .gif--small .gif__picture {
        max-height: 200px;
        margin-bottom: 16px;
      }

      .gif--small .gif__picture label::before {
        width: 85px;
        height: 85px;
      }

      .gif--small .gif__picture label::after {
        width: 95px;
        height: 95px;

        background-size: 95px;
      }

      .gif--small .gif__desctiption-title {
        width: auto;
      }

      .gif-list__item {
        margin-right: 40px;
        margin-bottom: 49px;
      }

      .gif-list__item:nth-child(3n) {
        margin-right: 0;
      }

      .gif__picture {
        position: relative;

        margin-bottom: 15px;
      }

      .gif__picture img {
        position: relative;
        top: 50%;
        left: 50%;

        display: block;

        transform: translate(-50%, -50%);
      }

      .gif__picture label {
        position: absolute;
        z-index: 1;

        width: 100%;
        height: 100%;
        padding: 0;

        font-size: 0;

        background-color: transparent;
        border: 0;
      }

      .gif__picture label::before,
      .gif__picture label::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;

        margin: auto;
      }

      .gif__picture label::before {
        width: 80px;
        height: 80px;

        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 50%;
      }

      .gif__picture label::after {
        width: 90px;
        height: 90px;

        background-image: url("/img/play.svg");
        background-repeat: no-repeat;
        background-position: 0 0;
      }

      .gif__picture label:hover::before,
      .gif__picture label:focus::before {
        background-color: #ffffff;
      }

      .gif__picture label:active {
        background-color: rgba(255, 255, 255, 0.3);
      }

      .gif__desctiption {
        margin-bottom: 10px;
      }

      .gif__desctiption-title {
        width: 90%;
        margin-top: 0;
        margin-bottom: 13px;

        font-weight: 400;
        font-size: 16px;
        line-height: 1.2;
      }

      .gif__desctiption-title a {
        color: #0060c0;
      }

      .gif__desctiption-title a:hover,
      .gif__desctiption-title a:focus {
        color: #004285;
      }

      .gif__desctiption-title a:active {
        opacity: 0.3;
      }

      .gif__description-data {
        display: flex;

        font-size: 14px;
        color: #8f8f8f;
      }

      .gif__description-data > :first-child {
        flex-grow: 1;
      }

      .gif__likes {
        position: relative;

        padding-right: 24px;
      }

      .gif__likes::after {
        content: "";
        position: absolute;
        right: 0;

        width: 15px;
        height: 15px;

        background-image: url("/img/heart.svg");
        background-repeat: no-repeat;
        background-position: 0 0;
      }

      .gif__views {
        position: relative;

        padding-right: 30px;
      }

      .gif__views::after {
        content: "";
        position: absolute;
        right: 0;

        width: 22px;
        height: 13px;

        background-image: url("/img/eye.svg");
        background-repeat: no-repeat;
        background-position: 0 0;
      }

      .gif__views + .gif__likes {
        margin-left: 34px;
      }

      .gif__control {
        width: 250px;
      }

      .gif__control + .gif__control {
        margin-left: 15px;
      }

      .gif__control--active {
        opacity: 0.5;
      }

      .gif + .comment-list {
        margin-bottom: 54px;
      }

      .gif + .comment-list,
      .comment-list + .comment-form {
        margin-top: 35px;
        padding-top: 38px;
      }

      .gif + .comment-list::after,
      .comment-list + .comment-form::after {
        content: "";
        position: absolute;
        top: 0;
        left: -100px;

        width: calc(100% + 120px);

        border-top: 1px solid #e5e5e5;
      }

      .comment-list,
      .comment-form {
        position: relative;
      }

      .comment-list__title {
        margin-top: 0;
        margin-bottom: 40px;

        font-weight: 400;
        font-size: 18px;
        color: #444444;
      }

      .comment {
        display: flex;
      }

      .comment + .comment {
        margin-top: 30px;
      }

      .comment__picture {
        flex-shrink: 1;
        margin-right: 28px;
      }

      .comment__author {
        width: 100%;
        margin-bottom: 6px;

        color: #8f8f8f;
      }

      .comment__text {
        width: 100%;
        margin: 0;
        margin-bottom: 10px;

        color: #444444;
      }

      .comment-form__label {
        display: inline-block;
        margin-bottom: 20px;

        font-size: 18px;
        color: #444444;
      }

      .comment-form__text {
        width: 498px;
        height: 59px;
        margin-bottom: 26px;
        padding: 10px;

        border: 1px solid #e6e6e6;
      }

      .comment-form__button {
        width: 250px;
      }

      .pagination {
        display: flex;
        justify-content: space-between;
      }

      .pagination__control {
        display: flex;
        margin: 0;
        padding: 0;

        list-style: none;
      }

      .pagination__item {
        margin: -1px 0 0 -1px;
      }

      .pagination__control a,
      .pagination__control span {
        position: relative;

        display: flex;
        justify-content: center;
        align-items: center;
        width: 88px;
        height: 84px;

        color: #444444;

        background-color: #ffffff;
        border: 1px solid #e5e5e5;
      }

      .pagination__control a {
        text-decoration: none;
      }

      .pagination__control a:hover,
      .pagination__control a:focus {
        color: #000000;
        text-decoration: none;

        border-color: #d0d0d0;
      }

      .pagination__control a:active {
        color: #b2b2b2;

        border-color: #f1f1f1;
      }

      .pagination__item--active a,
      .pagination__item--active a:hover,
      .pagination__item--active a:focus {
        color: #444444;

        background-color: #f2f2f2;
        border: 1px solid #e5e5e5;
      }

      .form__columns {
        display: flex;
      }

      .form__column {
        width: 460px;
      }

      .form__column--short {
        width: 315px;
      }

      .form__column + .form__column {
        margin-left: 60px;
      }

      .form__column + .form__controls {
        margin-top: 60px;
      }

      .form__columns + .form__controls {
        margin-top: 40px;
      }

      .form__row {
        position: relative;

        display: flex;
        width: 100%;
        margin-bottom: 5px;
      }

      .form__input--select {
        height: 55px;

        background-image: url("/img/dropdown.svg");
        background-repeat: no-repeat;
        background-position: right 10px top 50%;
        border-radius: 0;

        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
      }

      .form__input {
        width: 356px;
        padding: 15px 17px;
        box-sizing: border-box;

        line-height: 1.3;

        background-color: #ebecee;
        border: 3px solid transparent;
      }

      .form__input:hover {
        background-color: #e2e3e6;
      }

      .form__input:focus {
        background-color: transparent;
        border: 1px solid #3b4154;
      }

      .form__input--error,
      .form__input--error:hover,
      .form__input--error:focus {
        background-color: transparent;
        border-color: #f18081;
      }

      .form__input-file label {
        display: inline-block;
        width: 192px;
        padding: 16px 0;

        font-size: 18px;
        text-align: center;
        color: #ffffff;
        text-transform: uppercase;

        background-color: #3b4154;
      }

      .form__input-file label:hover,
      .form__input-file label:focus {
        background-color: #021628;
      }

      .form__input-file label:active {
        color: rgba(255, 255, 255, 0.3);
      }

      .form__label {
        flex-shrink: 0;
        min-width: 80px;
        max-width: 84px;
        margin-top: 16px;
        margin-right: 20px;

        font-size: 18px;
        color: #444444;
      }

      .form__controls {
        font-size: 18px;
      }

      .form__control {
        margin-right: 40px;
        margin-left: 100px;
      }

      .preview {
        position: relative;
      }

      .preview__img {
        display: block;
      }

      .preview__remove {
        position: absolute;
        top: -19px;
        right: -19px;

        width: 40px;
        height: 40px;
        padding: 0;

        font-size: 0;

        background-color: #3b4154;
        border: none;
        border-radius: 50%;
      }

      .preview__remove:hover,
      .preview__remove:focus {
        background-color: #021628;
      }

      .preview__remove::after {
        content: "";
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;

        width: 9px;
        height: 9px;
        margin: auto;

        background-image: url("/img/cross.svg");
      }

      .preview__remove:active::after {
        opacity: 0.3;
      }

      .error-notice {
        position: absolute;
        top: 14px;
        right: -8px;
        z-index: 1;
      }

      .error-notice__icon {
        position: absolute;

        width: 33px;
        height: 29px;

        font-size: 0;

        background-image: url("/img/error.svg");
        background-repeat: no-repeat;
        background-position: 50% 50%;
      }

      .error-notice__icon:hover + .error-notice__tooltip {
        display: block;
      }

      .error-notice__tooltip {
        position: absolute;
        top: 50px;
        left: -10px;

        display: none;
        width: 240px;
        padding: 30px;

        font-size: 14px;
        color: rgba(255, 255, 255, 0.7);

        background-color: #3b4154;
      }

      .error-notice__tooltip::before {
        content: "";
        position: absolute;
        top: -18px;
        left: 26px;

        border: 9px solid;
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: #3b4154;
        border-left-color: #3b4154;
      }

      .main-footer {
        display: flex;
        justify-content: flex-end;
        margin-right: 120px;
        margin-bottom: 75px;
      }

      .main-footer a {
        color: inherit;
      }

      .main-footer a:hover,
      .main-footer a:focus {
        opacity: 0.6;
      }

      .main-footer a:active {
        opacity: 0.3;
      }

      .main-footer__col {
        width: 350px;
        margin-right: 50px;
      }

      .main-footer__col--short {
        width: auto;
        margin-right: 0;
      }

      .copyright-logo {
        position: relative;

        display: inline-block;
        width: 34px;
        height: 34px;
      }

      a.copyright-logo:hover,
      a.copyright-logo:focus,
      a.copyright-logo:active {
        opacity: 1;
      }

      .copyright-logo::before {
        content: "";
        position: absolute;

        width: 58px;
        height: 58px;
        margin-top: -13px;
        margin-left: -13px;

        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
      }

      .copyright-logo:hover::before,
      .copyright-logo:focus::before {
        width: 80px;
        height: 80px;
        margin-top: -24px;
        margin-left: -24px;

        border: 1px solid rgba(255, 255, 255, 0.3);
      }

      .copyright-logo:active img {
        opacity: 0.3;
      }

      .copyright-logo img {
        position: absolute;
        top: 4px;
        right: 0;
        bottom: 0;
        left: 0;

        margin: auto;
      }
      .mesage_error {
          color: #cc0000;
      }
      .form__controls input[type="submit"]:disabled {
        background-color: #baf388;
      }
      span#email_valid  {
        font-size: 35px;
        color: #53b100;
        display: none;
      }
      span#pass_valid  {
        font-size: 35px;
        color: #53b100;
        display: none;
      }
      span#conf_pass_valid  {
        font-size: 35px;
        color: #53b100;
        display: none;
      }
      span#name_valid  {
        font-size: 35px;
        color: #53b100;
        display: none;
      }
      #s-h-pass {
        height: 30px;
        display: block;
      }
      input {
        outline: none;
      }
      .success_message {
        color: #0dcc00;
      }
      .block_for_messages {
        color: green;
      }
      .verify-block {
          position: relative;
          height: 30px;
          padding: 19px;
          background-color: #dfdfdf;
          font-weight: bold;
          font-size: x-large;
      }
      .verify-block .edit-field {
          position: absolute;
          right: -49px;
          top: 10px;
          width: 50px;
      }
      .verify-block .form-control {
        display: block;
          font-size: 14px;
          line-height: 1.42857143;
          color: #555;
          width: 100%;
          height: 38px;
          padding: 6px 12px;
          background-color: #fff;
          background-image: none;
          border: 1px solid #ccc;
          border-radius: 4px;
          -webkit-box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
          box-shadow: inset 0 1px 1px rgb(0 0 0 / 8%);
          -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
          -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
          transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
      }
      .gif__description {
        color: #000000;
      }

      .gif__picture img {
        max-width: 520px;
      }

      .form__errors {
        margin-bottom: 20px;
        padding: 10px;

        text-align: left;
        color: #000000;

        background-color: #f27458;
      }

      .hide {
        display: none;
      }

      #gifControl:checked + label {
        opacity: 0;
      }

      #gifControl:checked + label + img + img {
        display: none;
      }

      .gif_img.preview {
        position: absolute;
      }

      .gif__preview {
        position: relative;
        width: 100%;
        height: 260px;
        display: block;
      }

      .gif--small .gif__preview {
        height: 200px;
      }

      .gif__preview:hover {
        background-color: #ffffff;
        opacity: 0.5;
      }
      .loading-overlay {
          
          position: absolute;
          left: 0;
          top: 0;
          right: 0;
          bottom: 0;
          z-index: 2;
          background: rgba(255,255,255,0.7);
      }
      .overlay-content {
          position: absolute;
          transform: translateY(-50%);
          -webkit-transform: translateY(-50%);
          -ms-transform: translateY(-50%);
          top: 50%;
          left: 0;
          right: 0;
          text-align: center;
          color: #555;
      }
      .content {
          position: relative;
      }
      .modal--overflow {
        overflow: hidden;
      }
      body {
        position: relative;
      }
      .overlay {
        width: 100%;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 40px;
        position: fixed;
        top: 0;
        left: 0;
        background: rgba(0, 0, 0, 0.75);
        opacity: 0;
        pointer-events: none;
        transition: 0.35s ease-in-out;
        height: 100%;
        overflow-y: auto;
      }
      .overlay.open {
        opacity: 1;
        pointer-events: inherit;
      }
      .overlay .modal {
        background: white;
        text-align: center;
        padding: 40px 80px;
        box-shadow: 0px 1px 10px rgba(255, 255, 255, 0.35);
        opacity: 0;
        pointer-events: none;
        transition: 0.35s ease-in-out;
        max-height: 100vh;
        overflow-y: auto;
      }
      .overlay .modal.open {
        opacity: 1;
        pointer-events: inherit;
      }
      .overlay .modal.open .modal__content {
        transform: translate(0, 0px);
        opacity: 1;
      }
      .overlay .modal .modal__content {
        transform: translate(0, -10px);
        opacity: 0;
        transition: 0.35s ease-in-out;
      }
      .close-modal {
          text-align: right;
          display: block;
      }
      .disabled {
          color: #99929c!important;
      }
      .success_messages {
        color: green;
      }
      .table-bordered {
          border: 1px solid #ddd;
      }
      table {
          border-collapse: separate;
          text-indent: initial;
          white-space: normal;
          line-height: normal;
          font-weight: normal;
          font-size: medium;
          font-style: normal;
          color: -internal-quirk-inherit;
          text-align: start;
          border-spacing: 2px;
          font-variant: normal;
      }
      .table {
          width: 100%;
          max-width: 100%;
          margin-bottom: 20px;
      }
      table {
          background-color: transparent;
      }
      table {
          border-spacing: 0;
          border-collapse: collapse;
      }
      .table-striped>tbody>tr:nth-of-type(odd) {
          background-color: #f9f9f9;
      }
      .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
          border: 1px solid #ddd;
      }
      .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
          padding: 8px;
          line-height: 1.42857143;
          vertical-align: top;
          border-top: 1px solid #ddd;
          color: black;
      }
      th {
          text-align: left;
      }
      th {
          display: table-cell;
          vertical-align: inherit;
          font-weight: bold;
          text-align: -internal-center;
      }
      tr {
          display: table-row;
          vertical-align: inherit;
          border-color: inherit;
      }
      .label-search {
          color: #0e0e0e;
      }
      .gif--large {
          width: 520px;
          padding-bottom: 40px;
      }
      #show_more, #show_less {
        margin-top: 35px;
        margin-bottom: 35px;
      }
      .comment__edit {
          width: 24px;
          display: inline-block;
          margin-right: 5px;
          margin-bottom: -5px;
      }

      .edit {
        width: 326px;
        height: 140px;
        padding: 8px;
        line-height:20px;
        background-color: #fff;
        border: 2px solid #FFFF33;
        margin:2px;
      }
      .selected {
        padding: 10px;
        width: 360px;
        background-color: #FFFF99;
        border:1px #FFFF33 solid;
      }
      .save, .btnCancel {
        margin:0px 0px 0 5px;
      }
      #response {
        display:none;
        padding:10px;
        background-color:#9F9;
        border:2px solid #396;
        margin-bottom:20px;
      }
      #loading {
        display:none;
        width: 62px;
          position: absolute;
          top: 20%;
      }
      #success-response {
        color: green;
      }
    </style>
    <script async src="https://cdn.ampproject.org/v0.js"></script>
</head>
<body class="home">
    <div class="container">
        <header class="main-header">
            <h1 class="visually-hidden">Форум</h1>
            <a class="logo" href="/">
                <amp-img class="logo__img" src="/img/logo-2x.png" alt="Форум" width="160" height="38"></amp-img>
            </a>
            <form class="search" action="/search/search.php" method="get">
                <div class="search">
                    <div class="search__control">
                        <input type="text" name="q" id="search_box" class="search__text" placeholder="ПОИСК" />
                        <div class="search__submit">
                            <input class="button" type="submit" name="" value="Найти">
                        </div>
                    </div>
                </div>
            </form>
        </header>

        <div class="main-content">
            <section class="navigation">
                <h2 class="visually-hidden">Навигация</h2>
                <div class="navigation__item">
                    <h3 class="navigation__title navigation__title--account">Мой форум</h3>
                    <?php if (isset($_SESSION['user'])): ?>
                        <nav class="navigation__links">
                            <a href="javascript:;"><?= $username; ?></a>
                            <a href="/restore/reset_password.php">Изменить пароль</a>
                            <a href="/gif/favorites.php">Избранное</a>
                            <a href="/logout.php">Выход</a>
                        </nav>
                    <?php else : ?>
                        <nav class="navigation__links">
                           <?php $disabled = isset($signup_errors) ? "disabled" : "open-modal"; ?>
                            <a href="/signup/signup.php" class="<?= $disabled; ?>">Регистрация</a>
                            <?php $disabled = isset($signin_errors) ? "disabled" : "open-modal"; ?>
                            <a href="/signin/signin.php" class="<?= $disabled; ?>">Вход для своих</a>
                        </nav>
                    <?php endif; ?>
                </div>
                <div class="navigation__item">
                    <h3 class="navigation__title navigation__title--list">Категории</h3>
                    <nav class="navigation__links">
                        <?php foreach ($categories as $category): ?>
                            <?php if($category['upcategories_id']==0) : ?>
                                <a href="/category/<?= $category['urlCat']; ?>"><?= $category['nameCat']; ?></a>
                            <?php endif; ?>
                        <?php endforeach; ?>

                        
                        <?php foreach ($upcategories as $upcategory): ?>
                            <a class="upcategory" href="/upcategory/<?= $upcategory['url_up_Cat']; ?>"><?= $upcategory['name_up_Cat']; ?><div class="upcategory_after"></div></a>
                            <?php  $i = 0; ?>
                            <?php if($i==0) : ?><div class="upcategory_link"><?php endif; ?>
                            <?php foreach ($categories as $category): ?>
                                <?php if($upcategory['up_id']==$category['upcategories_id']) : ?>
                                    <a href="/category/<?= $category['urlCat']; ?>"><?= $category['nameCat']; ?></a><br>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <?php $i++; ?>
                            <?php if($i!==0) : ?></div><?php endif; ?>
                        <?php endforeach; ?>
                    </nav>
                </div>
            </section>
            <main class="content"><?= $content; ?></main>
        </div>

        <footer class="main-footer">
            <div class="main-footer__col">Если у вас вдруг возникли вопросы, свяжитесь с нами по почте: <a href="mailto:info@пппп.com">info@ппппп.com</a>.</div>

            <div class="main-footer__col">Сохранение смешных гифок разрешено только для личного использования.</div>
            <div class="main-footer__col">
             <p>Пользователей онлайн: <?= $num_online ?></p>
                <p>Уникальных посетителей за сутки: <?= $num_visitors_hosts; ?></p>
                <p>Просмотров за сутки: <?= $num_visitors_views; ?></p>
                <p>Уникальных посетителей за месяц: <?= $hosts_stat_month; ?></p>
                <p>Просмотров сайта за месяц: <?= $views_stat_month; ?></p>
             </div>
            <div class="main-footer__col main-footer__col--short">
                <a class="copyright-logo" href="/"><amp-img src="/img/htmlacademy.svg" alt="" width="27" height="34"></amp-img></a>
            </div>
        </footer>
    </div>
</body>
</html>
