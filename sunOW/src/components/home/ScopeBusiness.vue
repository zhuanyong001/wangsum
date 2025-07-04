<template>
    <div class="background">
        <img class="img" src="@/assets/images/Illustration2x.png" alt=""/>
        <div class="content">
            <div class="title">{{ $t('home.business_scope.title') }}</div>
            <div class="sub-title">{{ $t('home.business_scope.sub_title') }}</div>
            <div class="text-content">{{ $t('home.business_scope.text') }}</div>
        </div>
    </div>
</template>


<script setup>
import { ref, reactive, toRefs, onMounted, onBeforeUnmount, watch } from 'vue'

</script>

<style scoped lang="scss">
.background{
    background-color: #111;
    @include flex-row(75px);
    text-align:left;
    @include responseTo(mobile){
        @include flex-col(75px);
        flex-direction: column-reverse;
    }
    .img{
        display: block;
        width: 534px;
        @include responseTo(mobile){
            width: 50%;
        }
    }
    .content{
        .title{
            margin-bottom: 16px;
        }
        .sub-title{
            margin-bottom:33px;
        }
    }
}
</style>