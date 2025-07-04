<template>
    <div class="footer">
        <div class="frist flex-center">
            <img class="logo" src="@/assets/images/Group405202x.png" alt="">
            <div class="list">
                <div v-for="(item,index) in list1" :key="index" @click="item.active" class="curpo">
                    <img :src="item.icon" alt=""></img>
                </div>
            </div>
        </div>
        <div class="second flex-center">
            <div class="second-1">
                <div v-for="(item,index) in list2" :key="index" @click="handleNavigation(item.active)" class="curpo">
                    {{ t(item.label) }}
                </div>
            </div>
            <div class="second-2">
                <div v-for="(item,index) in contactInformation" :key="index">
                    <!-- <img :src="item.icon" alt=""></img> -->
                    <span>{{item.value}}</span>
                </div>
            </div>
        </div>
        <div class="third flex-center">
            <div>{{ t('footer.system_description') }}</div>
            <div>{{ t('footer.change_world') }}</div>
        </div>
    </div>
</template>

<script setup>
import { useI18n } from 'vue-i18n';
import tool from '@/utils/tools/tool';

const { t } = useI18n();

function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
        .then(() => {
            console.log('文本已复制到剪切板');
        })
        .catch(err => {
            console.error('复制失败:', err);
        });
}

const list1 = [
    {
        icon: new URL('@/assets/images/Button2x.png', import.meta.url),
        active: () => {
            window.location.href = 'https://x.com/dragonflyen_';
        }
    },
    {
        icon: new URL('@/assets/images/youtub.png', import.meta.url),
        active: () => {
            window.location.href = 'https://www.youtube.com/@Dragon_fly_global';
        }
    },
    {
        icon: new URL('@/assets/images/Button2x3.png', import.meta.url),
        active: () => {
            window.location.href = 'https://t.me/dragonfly_dao';
        }
    },
    {
        icon: new URL('@/assets/images/email.png', import.meta.url),
        active: () => {
            copyToClipboard('DragonflySwap@gmail.com');
            // 提示用户已复制
            alert(t('footer.email_copied'));
        }
    },
    // {
    //     icon: new URL('@/assets/images/Button2x4.png', import.meta.url)
    // }
];

const list2 = [
    {
        label: 'nav.home_page',
        active: 'home'
    },
    {
        label: 'nav.about_platform',
        active: 'concise'
    },
    {
        label: 'nav.team_members',
        active: 'JoinUsmy'
    },
    {
        label: 'nav.product_services',
        active: 'ProductService'
    },
    {
        label: 'nav.operating_principle',
        active: 'OperatingPrinciple'
    },
    {
        label: 'nav.dgfy_token',
        active: 'DGFYToken'
    },
    {
        label: 'nav.development_planning',
        active: 'DevelopmentPlanning'
    },
    {
        label: 'nav.cooperative_partner',
        active: 'CooperativePartner'
    }
];

const contactInformation = [
    {
        icon: new URL('@/assets/images/Icon2x.png', import.meta.url),
        value: 'DragonflySwap@gmail.com'
    },
    // {
    //     icon: new URL('@/assets/images/Icon2x1.png', import.meta.url),
    //     value: 'XXXXXXXXXX'
    // }
];

const handleNavigation = (active) => {
    tool.scrollToSection(active);
};
</script>
  
  <!-- Add "scoped" attribute to limit CSS to this component only -->
  <style scoped lang="scss">
  .flex-center{
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
.footer{
    color: #fff;
    padding: 48px 120px;
    @include responseTo(mobile){
        padding: 30px;
    }
    font-size: 16px;
    .frist{
        margin-bottom: 40px;
        .logo{
            width: 138px;
        }
        .list{
            @include flex-row(14px);
            
            img{
                width: 52px;
            }
        }
    }
    .second{
        border-top: 1px solid #26262622;
        margin-bottom:118px;
        &-1{
            @include flex-row(14px);
            @include responseTo(mobile){
                @include flex-col(14px);
            }
        }
        &-2{
            @include flex-row(14px);
            img{
                width: 24px;
            }
        }
    }
    .third{
        @include responseTo(mobile){
            @include flex-col(14px);
        }
    }
}
  </style>
  