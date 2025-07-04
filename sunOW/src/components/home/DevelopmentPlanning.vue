<template>
    <div class="background">
        <h1 class="title">{{ $t('home.development_planning.title') }}</h1>
        <p class="sub-title">
            {{ $t('home.development_planning.sub_title') }}
        </p>
        <div class="list">
            <div class="box" v-for="(item, index) in planningList" :key="index">
                <div class="head">
                    <img :src="item.icon" alt="" />
                    <div class="line"></div>
                </div>
                <div class="body">
                    <div class="title">{{ $t(item.title) }}</div>
                    <div class="text-content">
                        <p v-for="(text, textIndex) in item.content" :key="textIndex">{{ $t(text) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const planningList = [
    {
        icon: new URL('@/assets/images/Group13123217502x.png', import.meta.url),
        title: 'home.development_planning.list[0].title',
        content: [
            'home.development_planning.list[0].content[0]',
            'home.development_planning.list[0].content[1]',
            'home.development_planning.list[0].content[2]',
            'home.development_planning.list[0].content[3]',
            'home.development_planning.list[0].content[4]',
            'home.development_planning.list[0].content[5]'
        ]
    },
    {
        icon: new URL('@/assets/images/Group13123217512x.png', import.meta.url),
        title: 'home.development_planning.list[1].title',
        content: [
            'home.development_planning.list[1].content[0]',
            'home.development_planning.list[1].content[1]',
            'home.development_planning.list[1].content[2]',
            'home.development_planning.list[1].content[3]',
            'home.development_planning.list[1].content[4]'
        ]
    },
    {
        icon: new URL('@/assets/images/Group13123217522x.png', import.meta.url),
        title: 'home.development_planning.list[2].title',
        content: [
            'home.development_planning.list[2].content[0]',
            'home.development_planning.list[2].content[1]',
            'home.development_planning.list[2].content[2]',
            'home.development_planning.list[2].content[3]',
            'home.development_planning.list[2].content[4]',
            'home.development_planning.list[2].content[5]',
            'home.development_planning.list[2].content[6]'
        ]
    }
];
</script>


  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.background{
    background-color: #111;
    
    text-align:left;
    @include flex-col(0px);
    .list{
        text-align:left;
        width: 100%;
        display: grid;
        // grid-template-columns: repeat(3,1fr);
        column-gap: 20px;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        .box{
            @include flex-col(29px);
            border-radius: 24px;
            padding: 48px 0px;
            .head{
                @include flex-row(20px);
                align-items: center;
                img{
                    width: 48px;
                }
                .line{
                    width: 100%;
                    height: 1px;
                    background: #7BCF0A;
                }
            }
            .body{
                @include flex-col(20px);
                padding-right: 20%;
                @include responseTo(mobile){
                    padding-right: 0px;
                }
                .title{
                    font-size: 24px;
                }
                .text-content{
                    font-size: 18px;
                }
            }
        }
    }
}
</style>
  