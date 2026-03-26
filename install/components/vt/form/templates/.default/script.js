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
      const values = getData();

      if (!validate(values)) {
        return;
      }

      try {
        const response = await BX.ajax.runAction("vt:forms.formResult.add", {
          data: { formId: state.apiFormId, values },
        });

        if (response.status === "success") {
          showSuccess();
        }
      } catch (e) {
        showFormError(
          e?.errors?.[0]?.message || e.message || "Неизвестная ошибка",
        );
      }
    };

    const getData = () => {
      const formData = new FormData(form);
      return Object.fromEntries(formData.entries());
    };

    const validate = (data) => {
      let valid = true;

      state.form.querySelectorAll(".form__field").forEach((el) => {
        if (!el) return;

        const field = el.querySelector("input, textarea");
        const label = el.querySelector("label").innerText;

        if (
          field &&
          field.classList.contains("js-required") &&
          (!data[field.name] || data[field.name].trim() === "")
        ) {
          showFieldError(el, `Поле "${label}" обязательно для заполнения`);
          valid = valid && false;
          return;
        }

        if (
          field &&
          data[field.name] &&
          field.classList.contains("form__field-phone") &&
          !/\+7 \d{3} \d{3} \d{2} \d{2}/.test(data[field.name])
        ) {
          showFieldError(
            el,
            `Поле "${label}" должно быть заполнено в формате +7 XXX XXX XX XX`,
          );
          valid = valid && false;
          return;
        }

        if (
          field &&
          data[field.name] &&
          field.classList.contains("form__field-email") &&
          !/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(
            data[field.name],
          )
        ) {
          showFieldError(
            el,
            `Поле "${label}" должно быть заполнено в формате email@example.com`,
          );
          valid = valid && false;
          return;
        }
      });

      return valid;
    };

    const applyMask = () => {
      state.form
        .querySelectorAll(".form__field-phone input")
        .forEach((input) => {
          new BX.MaskedInput({
            mask: "+7 999 999 99 99",
            input,
            placeholder: "_",
          });
        });
    };

    const resetForm = () => {
      state.form.reset();
      hideSuccess();
    };

    const showFieldError = (input, message) => {
      input.closest(".form__field").classList.add("invalid");
      input.closest(".form__field").querySelector(".field__error").innerText =
        message;
    };

    const showFormError = (message) => {
      const error = state.form.querySelector(".form__error");
      error.innerText = message;
    };

    const clearErrors = () => {
      state.form
        .querySelectorAll(".field__error")
        .forEach((el) => (el.innerText = ""));
      state.form
        .querySelectorAll(".form__field.invalid")
        .forEach((el) => el.classList.remove("invalid"));
      state.form.querySelector(".form__error").innerText = "";
    };

    const showSuccess = () => {
      state.form.querySelector(".form__success").classList.add("open");
    };

    const hideSuccess = () => {
      state.form.querySelector(".form__success").classList.remove("open");
    };

    init();
  };
})();
