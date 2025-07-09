import { h } from 'vue';

import CycleStat from './CycleStat.vue';

import modal from '@/components/dy/Modal/modal.js';

import { Modal } from 'ant-design-vue';

export const showCycleStatModal = () => {
  const newModal = h(
    Modal,
    {
      visible: true,
      title: '自定义 Modal',
      onCancel: modal.close,
      footer: null,
      width: '1200px',
      style: { maxHeight: '80vh', overflow: 'auto' },
    },
    {
      default: () => h(CycleStat, {}),
    }
  );
  modal.open({
    Modal: newModal,
    props: {
      title: '矿池周期统计',
    },
  });
};
