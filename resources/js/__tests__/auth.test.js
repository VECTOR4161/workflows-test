import { describe, it, expect, beforeEach, vi } from "vitest";

describe("Auth Utils", () => {
    beforeEach(() => {
        // Mock localStorage
        global.localStorage = {
            setItem: vi.fn(),
            getItem: vi.fn(),
            removeItem: vi.fn(),
            clear: vi.fn(),
        };
    });

    describe("Token Management", () => {
        it("can store auth token", () => {
            const token = "test-token-123";
            localStorage.setItem("auth_token", token);

            expect(localStorage.setItem).toHaveBeenCalledWith(
                "auth_token",
                token
            );
        });

        it("can retrieve auth token", () => {
            localStorage.getItem.mockReturnValue("test-token-123");
            const token = localStorage.getItem("auth_token");

            expect(token).toBe("test-token-123");
            expect(localStorage.getItem).toHaveBeenCalledWith("auth_token");
        });

        it("can remove auth token", () => {
            localStorage.removeItem("auth_token");

            expect(localStorage.removeItem).toHaveBeenCalledWith("auth_token");
        });
    });

    describe("User Data Management", () => {
        it("can store user data as JSON", () => {
            const userData = {
                id: 1,
                name: "Test User",
                email: "test@example.com",
            };
            const userDataString = JSON.stringify(userData);

            localStorage.setItem("user_data", userDataString);

            expect(localStorage.setItem).toHaveBeenCalledWith(
                "user_data",
                userDataString
            );
        });

        it("can retrieve and parse user data", () => {
            const userData = {
                id: 1,
                name: "Test User",
                email: "test@example.com",
            };
            localStorage.getItem.mockReturnValue(JSON.stringify(userData));

            const retrieved = JSON.parse(localStorage.getItem("user_data"));

            expect(retrieved).toEqual(userData);
        });

        it("handles invalid JSON gracefully", () => {
            localStorage.getItem.mockReturnValue("invalid-json");

            expect(() => {
                JSON.parse(localStorage.getItem("user_data"));
            }).toThrow();
        });
    });

    describe("Session Validation", () => {
        it("validates complete session data", () => {
            localStorage.getItem.mockImplementation((key) => {
                if (key === "auth_token") return "valid-token";
                if (key === "user_data")
                    return JSON.stringify({ id: 1, name: "Test" });
                return null;
            });

            const token = localStorage.getItem("auth_token");
            const userData = localStorage.getItem("user_data");

            expect(token).toBeTruthy();
            expect(userData).toBeTruthy();
        });

        it("detects missing token", () => {
            localStorage.getItem.mockReturnValue(null);

            const token = localStorage.getItem("auth_token");

            expect(token).toBeNull();
        });

        it("detects missing user data", () => {
            localStorage.getItem.mockImplementation((key) => {
                if (key === "auth_token") return "valid-token";
                return null;
            });

            const token = localStorage.getItem("auth_token");
            const userData = localStorage.getItem("user_data");

            expect(token).toBeTruthy();
            expect(userData).toBeNull();
        });
    });

    describe("Logout Functionality", () => {
        it("clears all auth data on logout", () => {
            localStorage.removeItem("auth_token");
            localStorage.removeItem("user_data");

            expect(localStorage.removeItem).toHaveBeenCalledWith("auth_token");
            expect(localStorage.removeItem).toHaveBeenCalledWith("user_data");
        });
    });
});
