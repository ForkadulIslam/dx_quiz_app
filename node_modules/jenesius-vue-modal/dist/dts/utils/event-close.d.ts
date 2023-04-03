/**
 * background - Attempt to close modal by clicking on the background.
 *
 */
export interface IEventClose {
    background: boolean;
    esc: boolean;
}
export declare function DtoEventClose(obj?: Partial<IEventClose>): IEventClose;
