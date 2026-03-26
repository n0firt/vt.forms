(() => {
  "use strict";

  window.VTForm = (formId, params) => {
    this.init();
  };

  window.VTForm.prototype.init = () => {};

  window.VRForm.prototype.send = async (data) => {
    try {
      const response = await BX.ajax.runAction("vt:forms.result.add", data);
      this.showResult(response.data.message);
    } catch (e) {
      this.showError(e.message);
    }
  };

  window.VRForm.prototype.validate = () => {};

  window.VRForm.prototype.reset = () => {};

  window.VRForm.prototype.showError = (message) => {};

  window.VRForm.prototype.showResult = (message) => {};
})();
