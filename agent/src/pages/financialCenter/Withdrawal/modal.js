import modal from '@/components/dy/Modal/modal.js';
import Index from './index.vue';

import { Modal } from 'ant-design-vue';

const showModal = (id) => {
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
      default: () => h(Index, { user_id: id }),
    }
  );
  modal.open({
    Modal: newModal,
    props: {
      title: '提现记录',
      submit: () => {},
    },
  });
};

export default showModal;
