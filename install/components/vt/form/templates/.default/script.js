(() => {
  "use strict";

  /**
   * @param {string} elementId - ID контейнера в DOM (для поиска формы)
   * @param {string} apiFormId - Символьный код формы для API (из .settings.php)
   */
  window.VTForm = function (elementId, apiFormId) {
    if (!elementId || !apiFormId) return;

    const form = BX(elementId);

    if (!form) return;

    const state = {
      form,
      apiFormId,
    };

    const init = () => {
      BX.bind(form, "submit", send);
      BX.bind(form.querySelector(".form__reset"), "click", resetForm);
      applyMask();
    };

    const send = async (event) => {
      event.preventDefault();
      clearErrors();
      const data = getData();

      if (!validate(data)) {
        return;
      }

      try {
        const response = await BX.ajax.runAction("vt:forms.formResult.add", {
          data: { formId: state.formId, fields: data },
        });

        if (response.status === "success") {
          /*************/
        }
      } catch (e) {
        /*************/
      }
    };

    const getData = () => {
      const formData = new FormData(form);
      return Object.fromEntries(formData.entries());
    };

    const validate = (data) => {
      let valid = true;

      form.querySelectorAll(".js-required").forEach((el) => {
        if (!el) return;

        const field = el.querySelector("input, textarea");
        const label = el.querySelector("label").innerText;

        if (field && (!data[field.name] || data[field.name].trim() === "")) {
          showFieldError(el, `Поле "${label}" обязательно для заполнения`);
          valid = valid && false;
          return;
        }

        if (
          field.name === "PHONE" &&
          !/\+7 \d{3} \d{3} \d{2} \d{2}/.test(data[field.name])
        ) {
          showFieldError(
            el,
            `Поле "${label}" должно быть в формате +7 XXX XXX XX XX`,
          );
          valid = valid && false;
          return;
        }
      });

      return valid;
    };

    const applyMask = () => {
      form.querySelectorAll('input[type="phone"]').forEach((input) => {
        new BX.MaskedInput({
          mask: "+7 999 999 99 99",
          input,
          placeholder: "_",
        });
      });
    };

    const resetForm = () => {
      /*************/
    };

    const showFieldError = (input, message) => {
      input.closest(".form__field").classList.add("invalid");
      input.closest(".form__field").querySelector(".form__error").innerText =
        message;
    };

    const showFormError = (message) => {
      const error = form.querySelector(".form__error");
      error.innerText = message;
    };

    const clearErrors = () => {
      form
        .querySelectorAll(".form__error")
        .forEach((el) => (el.innerText = ""));
      form
        .querySelectorAll(".form__input.invalid")
        .forEach((el) => el.classList.remove("invalid"));
    };

    const showSuccess = () => {
      const success = form
        .querySelector(".form__success")
        .classlist.add("open");
    };

    const hideSuccess = () => {
      const success = form
        .querySelector(".form__success")
        .classlist.remove("open");
    };

    init();
  };
})();
