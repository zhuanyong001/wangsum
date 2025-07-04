<template>
    <div class="background">
        <div class="content">
            <div class="title">{{ $t('home.product_service.title') }}</div>
            <div class="sub-title">{{ $t('home.product_service.sub_title') }}</div>
            <div class="list">
                <div v-for="(item, index) in services" :key="index" class="item">
                    <div class="title">{{ $t(item.title) }}</div>
                    <div class="text-content">{{ $t(item.content) }}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const services = [
    {
        title: 'home.product_service.list.staking.title',
        content: 'home.product_service.list.staking.content'
    },
    {
        title: 'home.product_service.list.liquidity_mining.title',
        content: 'home.product_service.list.liquidity_mining.content'
    },
    {
        title: 'home.product_service.list.interest_earning.title',
        content: 'home.product_service.list.interest_earning.content'
    },
    {
        title: 'home.product_service.list.digital_lending.title',
        content: 'home.product_service.list.digital_lending.content'
    }
];
</script>


<style scoped lang="scss">
.background{
    background-color: #000;
    @include flex-col(75px);
    text-align:left;
    .img{
        width: 534px;
    }
    .content{
        .title{
            margin-bottom: 16px;
        }
        .sub-title{
            margin-bottom:33px;
        }
        .list{
            display: grid;
            grid-template-columns: repeat(2,1fr);
            column-gap: 80px;
            row-gap: 104px;
            @include responseTo(mobile){
                grid-template-columns: repeat(1, 1fr);
            }
            .item{
                @include flex-col(30px);
                border-left: 1px solid #7BCF0A;
                padding-left: 30px;
                .title{
                    font-size: 32px;
                }
                .text-content{
                    font-size: 16px;
                }
            }
        }
    }
}
</style>