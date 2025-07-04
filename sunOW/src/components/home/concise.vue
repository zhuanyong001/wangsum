<template>
    <div class="background">
        <h1 class="title">{{ $t('home.platform_introduction.title') }}</h1>
        <p class="sub-title">
            {{ $t('home.platform_introduction.sub_title') }}
        </p>
        <div class="content">
            <div class="text-content">
                <p>
                    {{ $t('home.platform_introduction.text') }}
                </p>
            </div>
            <video class="img" src="/video/DragonFly.mp4" controls autoplay muted loop></video>
        </div>
    </div>
</template>

<script>
import backImg from '@/assets/images/image2x1.png';

export default {
    name: 'process',
    props: {
        msg: String
    },
    data() {
        return {
            active: '0',
            backimg: backImg,
            range: {
                minValue: 540,
                maxValue: 1200
            }
        };
    },
    methods: {
        getUseMouse() {
            return useMouse;
        }
    },
    setup() {
        // 可添加更多 setup 内容
    }
};
</script>

  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped lang="scss">
.background{
    background-color: #111;
    text-align:left;
    @include flex-col(0px);
    align-items: center;
    .content{
        .title{
            margin-bottom: 16px;
        }
        .sub-title{
            margin-bottom:33px;
        }
        .img{
            width: 100%;
        }
    }
}
</style>
  