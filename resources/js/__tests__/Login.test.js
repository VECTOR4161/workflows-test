import { describe, it, expect, beforeEach, vi } from "vitest";
import { mount } from "@vue/test-utils";
import Login from "../../../resources/js/components/auth/Login.vue";

describe("Login Component", () => {
    let wrapper;

    beforeEach(() => {
        // Mock axios
        global.axios = {
            post: vi.fn(),
        };

        // Mock localStorage
        global.localStorage = {
            setItem: vi.fn(),
            getItem: vi.fn(),
            removeItem: vi.fn(),
        };

        wrapper = mount(Login, {
            global: {
                stubs: {
                    // Stub any child components if needed
                },
            },
        });
    });

    it("renders login form", () => {
        expect(wrapper.find("form").exists()).toBe(true);
        expect(wrapper.find('input[type="email"]').exists()).toBe(true);
        expect(wrapper.find('input[type="password"]').exists()).toBe(true);
        expect(wrapper.find('button[type="submit"]').exists()).toBe(true);
    });

    it("has email and password data properties", () => {
        expect(wrapper.vm.credentials.email).toBeDefined();
        expect(wrapper.vm.credentials.password).toBeDefined();
    });

    it("updates email when input changes", async () => {
        const emailInput = wrapper.find('input[type="email"]');
        await emailInput.setValue("test@example.com");

        expect(wrapper.vm.credentials.email).toBe("test@example.com");
    });

    it("updates password when input changes", async () => {
        const passwordInput = wrapper.find('input[type="password"]');
        await passwordInput.setValue("password123");

        expect(wrapper.vm.credentials.password).toBe("password123");
    });

    it("has a login method", () => {
        expect(typeof wrapper.vm.login).toBe("function");
    });

    it("form has correct action on submit", () => {
        const form = wrapper.find("form");
        expect(form.attributes("action")).toBe(undefined); // Vue handles submit
    });
});
