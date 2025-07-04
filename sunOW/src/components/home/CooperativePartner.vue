<template>
    <div class="background">
        <h1 class="title">{{ $t('home.collaboration_partners.title') }}</h1>
        <p class="sub-title">
            {{ $t('home.collaboration_partners.sub_title') }}
        </p>
        <div class="list">
            <div class="box" v-for="(item, index) in list" :key="index">
                <img :src="item.icon" alt="" />
            </div>
        </div>
    </div>
</template>
  
<script setup>
// import { useMouse } from '@/directive/mouse';
const list = [
    {
        icon:new URL('@/assets/images/Group13123217692x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217702x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217712x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217722x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217732x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217742x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217762x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217772x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217782x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217792x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217802x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217812x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217822x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217832x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217842x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217852x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217862x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217872x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217882x.png', import.meta.url),
    },
    {
        icon:new URL('@/assets/images/Group13123217892x.png', import.meta.url),
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
        grid-template-columns: repeat(auto-fill, minmax(248px, 1fr));
        @include responseTo(mobile){
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
        gap: 30px;
        .box{
            border-radius: 24px;
            width: 248px;
            height: 60px;
            @include responseTo(mobile){
                width: 200px;
            }
            img{
                width: 100%;
            }
        }
    }
}
</style>
  