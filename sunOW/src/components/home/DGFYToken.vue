<template>
    <div class="background">
        <div class="title">{{ $t('home.dgfy_token.title') }}</div>
        <div class="content">
            <img class="img" src="@/assets/images/Group13123217682x.png" alt="" />
            <div class="box">
                <div class="text-content">{{ $t('home.dgfy_token.description') }}</div>
                <div class="title">{{ $t('home.dgfy_token.allocation_title') }}</div>
                <div class="list">
                    <div class="item" v-for="(item, index) in list" :key="index">
                        <div class="title">{{ $t(item.title) }}</div>
                        <div class="num" :style="{ color: item.color }">{{ item.num }}</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer">
            <div v-for="(item, index) in footerList" :key="index" class="item">
                <div class="label">{{ $t(item.label) }}</div>
                <div class="value">{{ $t(item.value) }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const list = [
    {
        title: 'home.dgfy_token.list[0].title',
        num: t('home.dgfy_token.list[0].num'),
        color: t('home.dgfy_token.list[0].color')
    },
    {
        title: 'home.dgfy_token.list[1].title',
        num: t('home.dgfy_token.list[1].num'),
        color: t('home.dgfy_token.list[1].color')
    },
    {
        title: 'home.dgfy_token.list[2].title',
        num: t('home.dgfy_token.list[2].num'),
        color: t('home.dgfy_token.list[2].color')
    },
    {
        title: 'home.dgfy_token.list[3].title',
        num: t('home.dgfy_token.list[3].num'),
        color: t('home.dgfy_token.list[3].color')
    },
    {
        title: 'home.dgfy_token.list[4].title',
        num: t('home.dgfy_token.list[4].num'),
        color: t('home.dgfy_token.list[4].color')
    }
];

const footerList = [
    {
        label: 'home.dgfy_token.footer[0].label',
        value: 'home.dgfy_token.footer[0].value'
    },
    {
        label: 'home.dgfy_token.footer[1].label',
        value: 'home.dgfy_token.footer[1].value'
    },
    {
        label: 'home.dgfy_token.footer[2].label',
        value: 'home.dgfy_token.footer[2].value'
    },
    {
        label: 'home.dgfy_token.footer[3].label',
        value: 'home.dgfy_token.footer[3].value'
    }
];
</script>


<style scoped lang="scss">
.background{
    background-color: #111;
    @include flex-col(80px);
    text-align:left;
    
    .content{
        @include flex-row(123px);
        @include responseTo(mobile){
            @include flex-col(14px);
        }
        .img{
            width: 360px;
        }
        .box{
            @include flex-col(26px);
            .title{
                font-size: 20px;
            }
            .list{
                @include flex-row(88px);
                flex-wrap: wrap;
                row-gap: 30px;
                .item{
                    @include flex-col(14px);
                    .title{
                        color: #B3B3B3;
                        font-size: 16px;
                    }
                }
            }
        }
    }
    .footer{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        .item{
            @include flex-col(14px);
            width: 200px;
            align-items: center;
            .label{
                font-size: 16px;
                color: #B3B3B3;
            }
            .value{
                font-size: 24px;
                color: #9CC634;
            }
        }
        
    }
}
</style>