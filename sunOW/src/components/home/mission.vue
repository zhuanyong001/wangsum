<template>
    <div class="background">
        <div class="box">
            <img class="img" src="@/assets/images/ImageContainer2x.png" alt=""/>
            <div class="content">
                <div class="title">{{ $t('home.mission_vision.mission.title') }}</div>
                <div class="sub-title">{{ $t('home.mission_vision.mission.sub_title') }}</div>
                <div class="text-content">{{ $t('home.mission_vision.mission.text') }}</div>
            </div>
        </div>
        <div class="box">
            <div class="content">
                <div class="title">{{ $t('home.mission_vision.vision.title') }}</div>
                <div class="sub-title">{{ $t('home.mission_vision.vision.sub_title') }}</div>
                <div class="text-content">{{ $t('home.mission_vision.vision.text') }}</div>
            </div>
            <img class="img" src="@/assets/images/ImageContainer2x1.png" alt=""/>
        </div>
    </div>
</template>


<script setup>
import { ref, reactive, toRefs, onMounted, onBeforeUnmount, watch } from 'vue'

</script>

<style scoped lang="scss">
.background{
    background-color: #000;
    
    text-align:left;
    @include flex-col(30px);
    .box{
        @include flex-row(75px);
        @include responseTo(mobile){
            column-gap: 30px;
        }
        .img{
            max-width:50%;
            min-width: 30%;
            
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
    
}
</style>