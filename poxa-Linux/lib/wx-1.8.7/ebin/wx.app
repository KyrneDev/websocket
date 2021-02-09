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
  wxStaticBoxSizer, wxUpdateUIEvent, wxHelpEvent, wxIcon, wxColourPickerEvent, wxBitmapButton, wxImage, wxGraphicsContext, wxPreviewFrame, wxFontPickerCtrl, wxFileDialog, wxFlexGridSizer, wxPrintDialogData, wxFocusEvent, wxColourData, wxDisplay, wxDCOverlay, wxClipboardTextEvent, wxMoveEvent, wxChoicebook, wxSystemOptions, wxGridCellFloatRenderer, wxWindowDC, wxColourDialog, wxStatusBar, wxInitDialogEvent, wxEvent, wxXmlResource, wxGraphicsObject, wxPrintout, wxSysColourChangedEvent, wxListCtrl, wxLocale, wxGraphicsMatrix, wxBitmap, wxQueryNewPaletteEvent, wxCalendarCtrl, wxSizerItem, wxGridCellBoolRenderer, wxPasswordEntryDialog, wxFrame, wxNavigationKeyEvent, wxGraphicsRenderer, wxMouseCaptureLostEvent, wxTextEntryDialog, wxIdleEvent, wxStyledTextCtrl, wxListItem, wxSpinCtrl, wxControlWithItems, wxMDIChildFrame, wxStdDialogButtonSizer, wxFontPickerEvent, wxPrintPreview, wxPrintData, wxDirPickerCtrl, wxKeyEvent, wxEraseEvent, wxRadioBox, wxCalendarDateAttr, wxGridCellEditor, wxTreebook, wxSizeEvent, wxLogNull, wxGridCellStringRenderer, wxPreviewCanvas, wxTextAttr, wxScrollWinEvent, wxGraphicsBrush, wxWindowDestroyEvent, wxFontDialog, wxChoice, wxControl, wxToggleButton, wxGraphicsFont, wxStaticText, wxIconizeEvent, wxPostScriptDC, wxJoystickEvent, wxPrinter, wxStaticBitmap, wxGridBagSizer, wxListbook, wxGridSizer, wxScrollEvent, wx_misc, wxWindowCreateEvent, wxSashLayoutWindow, wxGridCellFloatEditor, wxPrintDialog, wxStaticBox, wxBufferedDC, wxTextCtrl, wxDateEvent, wxCalendarEvent, wxGauge, wxSizerFlags, wxGridCellTextEditor, wxDataObject, wxEvtHandler, wxShowEvent, wxBitmapDataObject, wxFindReplaceDialog, wxPageSetupDialogData, wxGraphicsPath, wxMiniFrame, wxDisplayChangedEvent, wxListEvent, wxDialog, wxPaintDC, wxTreeCtrl, wxScreenDC, wxPopupWindow, wxChildFocusEvent, wxColourPickerCtrl, wxFilePickerCtrl, wxFindReplaceData, wxGrid, wxAuiSimpleTabArt, wxSashEvent, wxScrolledWindow, wxMask, wxFontData, wxSplitterEvent, wxScrollBar, wxMenu, wxCheckBox, wxListItemAttr, wxMirrorDC, wxAuiManager, wxBoxSizer, wxMouseCaptureChangedEvent, wxClipboard, wxMouseEvent, wxStyledTextEvent, wxMDIClientWindow, wxSashWindow, wxAuiPaneInfo, wxPaintEvent, wxSplitterWindow, wxProgressDialog, wxGridCellNumberEditor, wxListBox, wxActivateEvent, wxNotebookEvent, wxFileDirPickerEvent, wxMenuItem, wxCursor, wxMessageDialog, wxButton, wxMenuBar, wxMaximizeEvent, wxToolBar, wxGraphicsPen, wxGridCellNumberRenderer, wxPaletteChangedEvent, wxArtProvider, wxHtmlEasyPrinting, wxRegion, wxListView, wxAuiManagerEvent, wxHtmlLinkEvent, wxGridEvent, wxBufferedPaintDC, wxContextMenuEvent, wxLayoutAlgorithm, wxCheckListBox, wxGridCellBoolEditor, wxMultiChoiceDialog, wxOverlay, wxTaskBarIconEvent, wxAuiDockArt, wxHtmlWindow, wxComboBox, wxCommandEvent, wxPanel, wxGridCellRenderer, wxGridCellAttr, wxGridCellChoiceEditor, wxImageList, wxAuiNotebook, wxNotifyEvent, wxToolTip, wxPalette, wxSlider, wxSizer, wxGBSizerItem, wxPen, wxBrush, wxAuiNotebookEvent, wxGLCanvas, wxAcceleratorEntry, wxTopLevelWindow, wxNotebook, wxSplashScreen, wxToolbook, wxPopupTransientWindow, wxGCDC, wxFileDataObject, wxRadioButton, wxPickerBase, wxCloseEvent, wxTextDataObject, wxDC, wxMemoryDC, wxCaret, wxAcceleratorTable, wxMenuEvent, wxMDIParentFrame, wxPreviewControlBar, wxStaticLine, wxGenericDirCtrl, wxFont, wxDatePickerCtrl, wxSystemSettings, wxWindow, wxTreeEvent, wxDropFilesEvent, wxSpinEvent, wxSingleChoiceDialog, wxSetCursorEvent, wxTaskBarIcon, wxAuiTabArt, wxIconBundle, wxClientDC, wxSpinButton, wxPageSetupDialog, wxDirDialog, glu, gl,
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
