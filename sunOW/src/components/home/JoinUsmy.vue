<template>
    <div class="background">
        <div class="searchBackimg">
            <div class="title">{{ t('home.participate.title') }}</div>
            <div class="sub-title">{{ t('home.participate.sub_title') }}</div>
            <div class="search">
                <input type="text" v-model="value" />
                <button @click="navto">{{ t('home.participate.launch') }}</button>
            </div>
        </div>

        <div class="content">
            <div class="title">{{ t('home.core_members.title') }}</div>
            <div class="sub-title">{{ t('home.core_members.sub_title') }}</div>
            <div class="text-content">{{ t('home.core_members.text') }}</div>
        </div>

        <div class="list">
            <div class="box" v-for="(item, index) in list" :key="index">
                <img :src="item.av" alt="" />
                <div class="title">{{ t(item.name) }}</div>
                <div class="sub-title">{{ t(item.subName) }}</div>
                <div class="text-content">{{ t(item.introduce) }}</div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();


const navto = () => {
    window.open(value.value);
};
const value = ref('https://www.dgfy.io');

const list = [
    {
        av: new URL('@/assets/images/Group13123217532x.png', import.meta.url),
        name: 'home.core_members.ceo.name',
        subName: 'home.core_members.ceo.subName',
        introduce: 'home.core_members.ceo.introduce'
    },
    {
        av: new URL('@/assets/images/Group13123217552x.png', import.meta.url),
        name: 'home.core_members.cto.name',
        subName: 'home.core_members.cto.subName',
        introduce: 'home.core_members.cto.introduce'
    },
    {
        av: new URL('@/assets/images/Group13123217562x.png', import.meta.url),
        name: 'home.core_members.coo.name',
        subName: 'home.core_members.coo.subName',
        introduce: 'home.core_members.coo.introduce'
    }
];
</script>


<style scoped lang="scss">
.background{
    background-color: #111;
    @include flex-col(75px);
    text-align:left;
    width: 100%;
    .searchBackimg{
        background-image: url('@/assets/images/Group13123217672x.png');
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
        width: 100%;
        /* 通过 padding-bottom 控制高度 */
        // aspect-ratio:1360/260;
        // padding-bottom: 19.5%;  /* 高度与宽度的比率 9 / 16 = 0.5625，即 56.25% */
        // position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 30px 40px;
        margin-bottom: 160px;
        @include responseTo(mobile){
            margin-bottom: 40px;
            padding: 30px 20px;
        }
        .title{
            margin-bottom: 1%;
            @include responseTo(mobile){
                font-size: 16px;
            }
        }
        .sub-title{
            margin-bottom:2%;
            @include responseTo(mobile){
                font-size: 12px;
            }
        }
        .search{
            @include flex-row(30px);
            input{
                width: 40%;
                border-radius: 30px;
                padding:15px 10px;
                background-color: #fff;
                @include responseTo(mobile){
                    padding: 5px 32px;
                }
            }
            button{
                background: #9CC634;
                border-radius: 30px;
                padding: 15px 64px;
                @include responseTo(mobile){
                    padding: 5px 32px;
                }
                color: #1C1C1C;
            }
        }
    }
    .content{
        margin-bottom: 48px;
        .title{
            margin-bottom: 16px;
        }
        .sub-title{
            margin-bottom:33px;
        }
    }

    .list{
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(370px, 1fr));
        grid-gap: 30px;
        text-align: center;
        // @include responseTo(mobile){
        //     grid-template-columns: repeat(1, 1fr);
        // }
        .box{
            flex-grow: 1;
            @include flex-col(30px);
            padding: 32px 48px;
            border-radius: 24px 24px 24px 24px;
            border:1px solid #eee2;
            align-items: center;
            img{
                width: 200px;
            }
            .title{
                font-size: 20px;
            }
            .sub-title{
                font-size: 16px;
                color: #B3B3B3;
            }
            .text-content{
                color: #5F5F5F;
                font-size: 16px;
            }
        }
    }
}
</style>