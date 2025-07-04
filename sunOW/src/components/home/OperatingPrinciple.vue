<template>
    <div class="background">
        <h1 class="title">{{ $t('home.operating_principle.title') }}</h1>
        <p class="sub-title">
            {{ $t('home.operating_principle.sub_title') }}
        </p>
        <div class="list">
            <div class="box" v-for="(item, index) in principles" :key="index">
                <img :src="item.icon" alt="" />
                <div class="title">{{ $t(item.title) }}</div>
                <div class="text-content">{{ $t(item.content) }}</div>
                <div class="jiantou">
                    <img src="@/assets/images/SVG2x1.png" alt="" />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const principles = [
    {
        icon: new URL('@/assets/images/Frame2x1.png', import.meta.url),
        title: 'home.operating_principle.list.decentralized_architecture.title',
        content: 'home.operating_principle.list.decentralized_architecture.content'
    },
    {
        icon: new URL('@/assets/images/Frame2x2.png', import.meta.url),
        title: 'home.operating_principle.list.automation_and_security.title',
        content: 'home.operating_principle.list.automation_and_security.content'
    }
];
</script>

  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.background{
    background-color: #111;
    
    text-align:center;
    @include flex-col(0px);
    .list{
        text-align:left;
        width: 100%;
        display: grid;
        grid-template-columns: repeat(2,1fr);
        gap: 44px;
        @include responseTo(mobile){
            grid-template-columns: repeat(1, 1fr);
        }
        .box{
            @include metal-color;
            @include flex-col(29px);
            border-radius: 24px;
            padding: 48px 40px;
            img{
                width: 48px;
            }
            .title{
                font-size: 24px;
            }
            .text-content{
                font-size: 18px;
            }
            .jiantou{
                position: absolute;
                right: 20px;
                img{
                    width: 24px;
                }
            }
        }
    }
}
</style>
  