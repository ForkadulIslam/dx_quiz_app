import { IEventClose } from "./event-close";
export declare type GuardFunction = (e: IEventClose) => void | boolean | Promise<boolean>;
export declare type GuardFunctionPromisify = () => Promise<void>;
export interface ModalComponentInterface {
    [name: string]: any;
}
