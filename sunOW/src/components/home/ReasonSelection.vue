<template>
    <div class="background">
        <!-- 使用 $t 方法进行国际化 -->
        <h1 class="title">{{ $t('home.reasons_to_choose.title') }}</h1>
        <p class="sub-title">
            {{ $t('home.reasons_to_choose.sub_title') }}
        </p>
        <div class="list">
            <div class="box" v-for="(item, index) in list" :key="index">
                <img :src="item.icon" alt="">
                <div class="title">{{ item.title }}</div>
                <div class="text-content">{{ item.content }}</div>
            </div>
        </div>
    </div>
</template>

  
<script setup>
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

const list = [
    {
        icon: new URL('@/assets/images/IconContainer2x2.png', import.meta.url),
        title: t('home.reasons_to_choose.list.diverse_defi_services.title'),
        content: t('home.reasons_to_choose.list.diverse_defi_services.content')
    },
    {
        icon: new URL('@/assets/images/IconContainer2x.png', import.meta.url),
        title: t('home.reasons_to_choose.list.flexible_revenue_models.title'),
        content: t('home.reasons_to_choose.list.flexible_revenue_models.content')
    },
    {
        icon: new URL('@/assets/images/IconContainer2x4.png', import.meta.url),
        title: t('home.reasons_to_choose.list.efficient_and_transparent.title'),
        content: t('home.reasons_to_choose.list.efficient_and_transparent.content')
    },
    {
        icon: new URL('@/assets/images/IconContainer2x3.png', import.meta.url),
        title: t('home.reasons_to_choose.list.decentralized_advantage.title'),
        content: t('home.reasons_to_choose.list.decentralized_advantage.content')
    },
    {
        icon: new URL('@/assets/images/IconContainer2x1.png', import.meta.url),
        title: t('home.reasons_to_choose.list.flexible_asset_growth.title'),
        content: t('home.reasons_to_choose.list.flexible_asset_growth.content')
    },
    {
        icon: new URL('@/assets/images/IconContainer2x5.png', import.meta.url),
        title: t('home.reasons_to_choose.list.professional_security.title'),
        content: t('home.reasons_to_choose.list.professional_security.content')
    }
]
</script>

  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.background{
    background-color: #000;
    
    text-align:left;
    @include flex-col(0px);
    .list{
        text-align: center;
        width: 100%;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        column-gap: 30px;
        row-gap: 40px;
        // @include responseTo(mobile){
        //     grid-template-columns: repeat(1, 1fr);
        // }
        .box{
            @include metal-color;
            border-radius: 24px;
            padding: 48px 40px;
            @include flex-col(20px);
            align-items: center;
            img{
                width: 88px;
            }
            .title{
                font-size: 24px;
            }
            .text-content{
                font-size: 16px;
            }
        }
    }
}
</style>
  