%% This is an -*- erlang -*- file.
%%
%% %CopyrightBegin%
%%
%% Copyright Ericsson AB 2010-2016. All Rights Reserved.
%%
%% Licensed under the Apache License, Version 2.0 (the "License");
%% you may not use this file except in compliance with the License.
%% You may obtain a copy of the License at
%%
%%     http://www.apache.org/licenses/LICENSE-2.0
%%
%% Unless required by applicable law or agreed to in writing, software
%% distributed under the License is distributed on an "AS IS" BASIS,
%% WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
%% See the License for the specific language governing permissions and
%% limitations under the License.
%%
%% %CopyrightEnd%

{application, wx,
 [{description, "Yet another graphics system"},
  {vsn, "1.8.7"},
  {modules,
   [
    %% Generated modules
  wxAcceleratorEntry, wxAcceleratorTable, wxActivateEvent, wxArtProvider, wxAuiDockArt, wxAuiManager, wxAuiManagerEvent, wxAuiNotebook, wxAuiNotebookEvent, wxAuiPaneInfo, wxAuiSimpleTabArt, wxAuiTabArt, wxBitmap, wxBitmapButton, wxBitmapDataObject, wxBoxSizer, wxBrush, wxBufferedDC, wxBufferedPaintDC, wxButton, wxCalendarCtrl, wxCalendarDateAttr, wxCalendarEvent, wxCaret, wxCheckBox, wxCheckListBox, wxChildFocusEvent, wxChoice, wxChoicebook, wxClientDC, wxClipboard, wxClipboardTextEvent, wxCloseEvent, wxColourData, wxColourDialog, wxColourPickerCtrl, wxColourPickerEvent, wxComboBox, wxCommandEvent, wxContextMenuEvent, wxControl, wxControlWithItems, wxCursor, wxDC, wxDCOverlay, wxDataObject, wxDateEvent, wxDatePickerCtrl, wxDialog, wxDirDialog, wxDirPickerCtrl, wxDisplay, wxDisplayChangedEvent, wxDropFilesEvent, wxEraseEvent, wxEvent, wxEvtHandler, wxFileDataObject, wxFileDialog, wxFileDirPickerEvent, wxFilePickerCtrl, wxFindReplaceData, wxFindReplaceDialog, wxFlexGridSizer, wxFocusEvent, wxFont, wxFontData, wxFontDialog, wxFontPickerCtrl, wxFontPickerEvent, wxFrame, wxGBSizerItem, wxGCDC, wxGLCanvas, wxGauge, wxGenericDirCtrl, wxGraphicsBrush, wxGraphicsContext, wxGraphicsFont, wxGraphicsMatrix, wxGraphicsObject, wxGraphicsPath, wxGraphicsPen, wxGraphicsRenderer, wxGrid, wxGridBagSizer, wxGridCellAttr, wxGridCellBoolEditor, wxGridCellBoolRenderer, wxGridCellChoiceEditor, wxGridCellEditor, wxGridCellFloatEditor, wxGridCellFloatRenderer, wxGridCellNumberEditor, wxGridCellNumberRenderer, wxGridCellRenderer, wxGridCellStringRenderer, wxGridCellTextEditor, wxGridEvent, wxGridSizer, wxHelpEvent, wxHtmlEasyPrinting, wxHtmlLinkEvent, wxHtmlWindow, wxIcon, wxIconBundle, wxIconizeEvent, wxIdleEvent, wxImage, wxImageList, wxInitDialogEvent, wxJoystickEvent, wxKeyEvent, wxLayoutAlgorithm, wxListBox, wxListCtrl, wxListEvent, wxListItem, wxListItemAttr, wxListView, wxListbook, wxLocale, wxLogNull, wxMDIChildFrame, wxMDIClientWindow, wxMDIParentFrame, wxMask, wxMaximizeEvent, wxMemoryDC, wxMenu, wxMenuBar, wxMenuEvent, wxMenuItem, wxMessageDialog, wxMiniFrame, wxMirrorDC, wxMouseCaptureChangedEvent, wxMouseCaptureLostEvent, wxMouseEvent, wxMoveEvent, wxMultiChoiceDialog, wxNavigationKeyEvent, wxNotebook, wxNotebookEvent, wxNotifyEvent, wxOverlay, wxPageSetupDialog, wxPageSetupDialogData, wxPaintDC, wxPaintEvent, wxPalette, wxPaletteChangedEvent, wxPanel, wxPasswordEntryDialog, wxPen, wxPickerBase, wxPopupTransientWindow, wxPopupWindow, wxPostScriptDC, wxPreviewCanvas, wxPreviewControlBar, wxPreviewFrame, wxPrintData, wxPrintDialog, wxPrintDialogData, wxPrintPreview, wxPrinter, wxPrintout, wxProgressDialog, wxQueryNewPaletteEvent, wxRadioBox, wxRadioButton, wxRegion, wxSashEvent, wxSashLayoutWindow, wxSashWindow, wxScreenDC, wxScrollBar, wxScrollEvent, wxScrollWinEvent, wxScrolledWindow, wxSetCursorEvent, wxShowEvent, wxSingleChoiceDialog, wxSizeEvent, wxSizer, wxSizerFlags, wxSizerItem, wxSlider, wxSpinButton, wxSpinCtrl, wxSpinEvent, wxSplashScreen, wxSplitterEvent, wxSplitterWindow, wxStaticBitmap, wxStaticBox, wxStaticBoxSizer, wxStaticLine, wxStaticText, wxStatusBar, wxStdDialogButtonSizer, wxStyledTextCtrl, wxStyledTextEvent, wxSysColourChangedEvent, wxSystemOptions, wxSystemSettings, wxTaskBarIcon, wxTaskBarIconEvent, wxTextAttr, wxTextCtrl, wxTextDataObject, wxTextEntryDialog, wxToggleButton, wxToolBar, wxToolTip, wxToolbook, wxTopLevelWindow, wxTreeCtrl, wxTreeEvent, wxTreebook, wxUpdateUIEvent, wxWindow, wxWindowCreateEvent, wxWindowDC, wxWindowDestroyEvent, wxXmlResource, wx_misc, glu, gl,
    %% Handcrafted modules
    wx,
    wx_object,
    wxe_master,
    wxe_server,
    wxe_util
   ]},
  {registered, []},
  {applications, [stdlib, kernel]},
  {env, []},
  {runtime_dependencies, ["stdlib-2.0","kernel-3.0","erts-6.0"]}
 ]}.
