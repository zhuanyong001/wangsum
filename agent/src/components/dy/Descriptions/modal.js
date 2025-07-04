import { createApp } from 'vue';
import Index from './index.vue';

const modal = (() => {
  let instance = null;

  const createModal = ({ Modal = null, props = {} }) => {
    if (instance) {
      instance.hide()
    }

    // 创建一个DOM元素来挂载Vue实例
    const rootNode = document.createElement('div');
    document.body.appendChild(rootNode);

    // 创建Vue实例并传递props
    const app = createApp(Modal || Index, {
      ...props,
    });

    instance = app.mount(rootNode);
    instance.hide = () => {
      app.unmount();
      document.body.removeChild(rootNode);
      instance = null;
    };

    return instance;
  };

  return {
    open({ Modal, props = {} }) {
      return createModal({ Modal, props });
    },
    close() {
      if (instance) {
        instance.hide();
      }
    },
  };
})();

export default modal;
